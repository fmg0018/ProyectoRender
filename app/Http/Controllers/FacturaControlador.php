<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FacturaModelo;
use App\Models\ClienteModelo;
use Illuminate\Support\Facades\Auth;
use App\Mail\FacturaEnviada;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class FacturaControlador extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorizeAccess();

        $query = FacturaModelo::with('cliente')->orderBy('fecha_emision', 'desc');

        // Aplicar filtros según el parámetro de consulta
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'vencidas':
                    $query->where('estado', 'pendiente')
                          ->where('fecha_vencimiento', '<', now());
                    break;
                case 'pendientes':
                    $query->where('estado', 'pendiente')
                          ->where('fecha_vencimiento', '>=', now());
                    break;
                case 'pagadas':
                    $query->where('estado', 'pagada');
                    break;
            }
        }

        $facturas = $query->paginate(15);

        // Pasar información de filtro a la vista
        $currentFilter = $request->get('filter', 'todas');

        return view('facturas.facturasVistaIndex', compact('facturas', 'currentFilter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeAccess();

        $clientes = ClienteModelo::orderBy('nombre')->get();
        return view('facturas.facturasVistaCreate', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeAccess();

        $rules = [
            'cliente_id' => 'nullable|exists:clientes,id',
            'cliente_name' => 'nullable|string',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'required|date|after_or_equal:fecha_emision',
            'subtotal' => 'required|numeric|min:0',
            'impuestos' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:pendiente,pagada,vencida,cancelada',
            'lineas' => 'nullable|array',
            'lineas.*.descripcion' => 'required_with:lineas|string',
            'lineas.*.cantidad' => 'required_with:lineas|integer|min:1',
            'lineas.*.precio_unitario' => 'required_with:lineas|numeric|min:0',
            'lineas.*.impuesto' => 'nullable|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

    $data = $validator->validated();

        // Si no se proporcionó cliente_id, intentar usar cliente_email o cliente_name para buscar/crear cliente
        if (empty($data['cliente_id'])) {
            // Si el formulario incluye cliente_email y no está vacío, priorizarlo
            if (!empty($data['cliente_email'])) {
                $cliente = ClienteModelo::firstOrCreate(
                    ['email' => $data['cliente_email']],
                    ['nombre' => $data['cliente_name'] ?? $data['cliente_email']]
                );
                $data['cliente_id'] = $cliente->id;
            } elseif (!empty($data['cliente_name'])) {
                // Generar email de cliente a partir del nombre (consistentemente)
                $generatedEmail = str_replace(' ', '_', strtolower($data['cliente_name'])) . '@example.com';

                // Intentar buscar por email primero (evita duplicados por unique constraint), si no existe buscar por nombre
                $cliente = ClienteModelo::where('email', $generatedEmail)->orWhere('nombre', $data['cliente_name'])->first();
                if (!$cliente) {
                    // Crear con firstOrCreate usando email como clave única de búsqueda
                    $cliente = ClienteModelo::firstOrCreate([
                        'email' => $generatedEmail,
                    ], [
                        'nombre' => $data['cliente_name'],
                    ]);
                }

                $data['cliente_id'] = $cliente->id;
            }
        }

        if (empty($data['cliente_id'])) {
            return redirect()->back()->withErrors(['cliente' => 'Debe seleccionar o escribir un cliente o indicar su email'])->withInput();
        }
        $data['impuestos'] = $data['impuestos'] ?? 0;

        // generar número de factura antes de insertar para cumplir la restricción NOT NULL
        try {
            $data['numero_factura'] = FacturaModelo::generarNumero();
        } catch (\Exception $e) {
            \Log::error('Error generando número de factura: ' . $e->getMessage());
            $data['numero_factura'] = strtoupper(Str::random(8));
        }

        // Crear factura initial sin totales definitivos
        $factura = FacturaModelo::create(array_merge($data, ['subtotal' => 0, 'impuestos' => 0, 'total' => 0]));

        // Procesar lineas si vienen
        $lineas = $request->input('lineas', []);
        foreach ($lineas as $l) {
            if (empty($l['descripcion'])) continue;
            $precio = isset($l['precio_unitario']) ? floatval($l['precio_unitario']) : 0;
            $cantidad = isset($l['cantidad']) ? intval($l['cantidad']) : 1;
            $impuesto = isset($l['impuesto']) ? floatval($l['impuesto']) : 0;

            // Interpretar impuesto como porcentaje
            $lineSubtotal = $cantidad * $precio;
            $lineImpuesto = ($impuesto != 0) ? ($lineSubtotal * ($impuesto / 100.0)) : 0;

            $factura->lineas()->create([
                'descripcion' => $l['descripcion'],
                'cantidad' => $cantidad,
                'precio_unitario' => $precio,
                'impuesto' => $impuesto,
                'total_linea' => round($lineSubtotal + $lineImpuesto, 2),
            ]);
        }

        // Recalcular totales: si hay lineas, se calculan desde ellas; si no, usar subtotal/impuestos proporcionados
        $factura->load('lineas');
        if ($factura->lineas()->count() > 0) {
            $factura->recalcularTotales();
        } else {
            $factura->subtotal = isset($data['subtotal']) ? round($data['subtotal'], 2) : 0;
            // Si el usuario pasó un valor en 'impuestos' lo interpretamos como porcentaje
            $impuestosPercent = isset($data['impuestos']) ? floatval($data['impuestos']) : 0;
            $impuestosMonetarios = round($factura->subtotal * ($impuestosPercent / 100.0), 2);
            $factura->impuestos = $impuestosMonetarios;
            $factura->total = round($factura->subtotal + $factura->impuestos, 2);
            $factura->save();
        }

        return redirect()->route('facturas.show', $factura->id)->with('success', 'Factura creada correctamente');
    }

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorizeAccess();

        $factura = FacturaModelo::with('cliente')->findOrFail($id);

        return view('facturas.facturasVistaShow', compact('factura'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorizeAccess();

        $factura = FacturaModelo::findOrFail($id);
        $clientes = ClienteModelo::orderBy('nombre')->get();

        return view('facturas.facturasVistaEdit', compact('factura', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorizeAccess();

        $factura = FacturaModelo::findOrFail($id);

        $rules = [
            'cliente_id' => 'nullable|exists:clientes,id',
            'cliente_name' => 'nullable|string',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'required|date|after_or_equal:fecha_emision',
            'subtotal' => 'required|numeric|min:0',
            'impuestos' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:pendiente,pagada,vencida,cancelada',
            'lineas' => 'nullable|array',
            'lineas.*.descripcion' => 'required_with:lineas|string',
            'lineas.*.cantidad' => 'required_with:lineas|integer|min:1',
            'lineas.*.precio_unitario' => 'required_with:lineas|numeric|min:0',
            'lineas.*.impuesto' => 'nullable|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

    $data = $validator->validated();

    // Capturar el porcentaje de impuestos enviado (si existe) y evitar que se escriba directamente
    // en la columna 'impuestos' antes de calcular el importe monetario.
    $impuestosPercentFromRequest = isset($data['impuestos']) ? floatval($data['impuestos']) : null;
    unset($data['impuestos']);

        if (empty($data['cliente_id'])) {
            if (!empty($data['cliente_email'])) {
                $cliente = ClienteModelo::firstOrCreate(
                    ['email' => $data['cliente_email']],
                    ['nombre' => $data['cliente_name'] ?? $data['cliente_email']]
                );
                $data['cliente_id'] = $cliente->id;
            } elseif (!empty($data['cliente_name'])) {
                $cliente = ClienteModelo::firstOrCreate([
                    'nombre' => $data['cliente_name'],
                ], [
                    'email' => str_replace(' ', '_', strtolower($data['cliente_name'])) . '@example.com'
                ]);

                $data['cliente_id'] = $cliente->id;
            }
        }

        if (empty($data['cliente_id'])) {
            return redirect()->back()->withErrors(['cliente' => 'Debe seleccionar o escribir un cliente'])->withInput();
        }
        $data['impuestos'] = $data['impuestos'] ?? 0;

        // Actualizar factura sin 'impuestos' (lo calcularemos a continuación según líneas o porcentaje)
        $factura->update($data);

        // Si el request incluye líneas, reemplazarlas y recalcular totales desde las líneas
        $lineas = $request->input('lineas', null);
        if (is_array($lineas)) {
            // Reemplazar lineas: borrar actuales y crear nuevas
            $factura->lineas()->delete();
            foreach ($lineas as $l) {
                if (empty($l['descripcion'])) continue;
                $precio = isset($l['precio_unitario']) ? floatval($l['precio_unitario']) : 0;
                $cantidad = isset($l['cantidad']) ? intval($l['cantidad']) : 1;
                $impuesto = isset($l['impuesto']) ? floatval($l['impuesto']) : 0;

                // Calcular correctamente el impuesto como porcentaje sobre la línea
                $lineSubtotal = $cantidad * $precio;
                $lineImpuesto = ($impuesto != 0) ? ($lineSubtotal * ($impuesto / 100.0)) : 0;

                $factura->lineas()->create([
                    'descripcion' => $l['descripcion'],
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'impuesto' => $impuesto,
                    'total_linea' => round($lineSubtotal + $lineImpuesto, 2),
                ]);
            }

            $factura->load('lineas');
            if ($factura->lineas()->count() > 0) {
                $factura->recalcularTotales();
            }
        } else {
            // No se enviaron líneas: interpretar el 'impuestos' enviado (si existe) como porcentaje y calcular importe monetario
            $factura->subtotal = isset($data['subtotal']) ? round($data['subtotal'], 2) : $factura->subtotal;
            $impuestosPercent = $impuestosPercentFromRequest !== null ? $impuestosPercentFromRequest : 0;
            $impuestosMonetarios = round($factura->subtotal * ($impuestosPercent / 100.0), 2);
            $factura->impuestos = $impuestosMonetarios;
            $factura->total = round($factura->subtotal + $factura->impuestos, 2);
            $factura->save();
        }

        return redirect()->route('facturas.show', $factura->id)->with('success', 'Factura actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorizeAdmin();

        $factura = FacturaModelo::findOrFail($id);
        $factura->delete();

        return redirect()->route('facturas.index')->with('success', 'Factura eliminada');
    }

    /**
     * Descargar PDF de la factura
     */
    public function downloadPdf(FacturaModelo $factura)
    {
        // generar vista del PDF
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('facturas.pdf', compact('factura'));
            return $pdf->download(($factura->numero ?? $factura->numero_factura ?? 'factura') . '.pdf');
        }

        return response()->view('facturas.pdf', compact('factura'));
    }

    /**
     * Enviar factura por email (adjunta PDF si está disponible)
     */
    public function enviarPorEmail(FacturaModelo $factura)
    {
        if (!$factura->cliente || !$factura->cliente->email) {
            return redirect()->back()->with('error', 'Cliente sin email configurado.');
        }

        Mail::to($factura->cliente->email)->send(new FacturaEnviada($factura));

        return redirect()->route('facturas.show', $factura)->with('success', 'Factura enviada por email.');
    }

    /**
     * Comprueba que el usuario esté autenticado y tenga acceso (admin o user)
     */
    protected function authorizeAccess()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'No autorizado');
        }
    }

    /**
     * Solo admin puede ejecutar ciertas acciones
     */
    protected function authorizeAdmin()
    {
        $user = Auth::user();
        if (!$user || ($user->role ?? 'user') !== 'admin') {
            abort(403, 'Acceso sólo para administradores');
        }
    }
}
