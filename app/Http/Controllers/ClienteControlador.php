<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClienteModelo; 
use Illuminate\Support\Facades\Log; 
use Illuminate\Http\JsonResponse;


class ClienteControlador extends Controller
{
    /**
     * Muestra una lista de todos los clientes.
     */
    public function index()
    {
        try {
            $clientes = ClienteModelo::orderBy('nombre')
                ->paginate(12)
                ->withQueryString();

            return view('clientes.clientesVistaIndex', compact('clientes'));
        } catch (\Exception $e) {
            // Manejo de error si la tabla no existe o hay problemas de DB
            Log::error("Error en index(): " . $e->getMessage());
            // Retornar una respuesta de error si falla la conexión a DB
            return response()->view('error', ['message' => 'No se pudo cargar la lista de clientes.'], 500);
        }
    }

    /**
     * Muestra el formulario para crear un nuevo cliente.
     */
    public function create()
    {
        // Retorna la vista con el formulario de creación
        return view('clientes.clientesVistaCreate');
    }

    /**
     * Almacena un nuevo cliente en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. **VALIDACIÓN**: Asegura que los datos son correctos.
        // La tabla real de la base de datos es 'clientes', según tu migración.
        $request->validate([
            // Estos campos aparecen como requeridos en la última imagen de error
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            
            // Corrección de la tabla para 'unique'
            'email' => 'nullable|email|unique:clientes,email', 
            
            // Campos opcionales que aparecen en tu migración
            'empresa' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'pais' => 'nullable|string|max:100',
            'direccion' => 'nullable|string|max:255',
        ]);

        try {
            // 2. **ALMACENAMIENTO**: Crea un nuevo registro con todos los datos validados
            ClienteModelo::create($request->all()); 
            
            // 3. **REDIRECCIÓN CON ÉXITO**
            return redirect()->route('clientes.index')->with('success', 'Cliente registrado exitosamente.');

        } catch (\Exception $e) {
            // 4. **MANEJO DE ERRORES**
            Log::error("Error al guardar cliente: " . $e->getMessage());
            return back()->withInput()->with('error', 'Error al guardar el cliente: ' . $e->getMessage());
        }
    }
    
    /**
     * Muestra el cliente especificado.
     */
    public function show(string $id)
    {
        $cliente = ClienteModelo::findOrFail($id);
        return view('clientes.clientesVistaShow', compact('cliente'));
    }

    /**
     * Muestra el formulario para editar el cliente especificado.
     */
    public function edit(string $id)
    {
        $cliente = ClienteModelo::findOrFail($id);
        return view('clientes.clientesVistaEdit', compact('cliente'));
    }

    /**
     * Actualiza el cliente especificado en la base de datos.
     */
    public function update(Request $request, string $id)
    {
        $cliente = ClienteModelo::findOrFail($id);

        // 1. **VALIDACIÓN para la actualización**: 'unique' debe ignorar el registro actual
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            
            // Corrección de la tabla e ignorando ID actual
            'email' => 'nullable|email|unique:clientes,email,' . $id, 
            
            'empresa' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'pais' => 'nullable|string|max:100',
            'direccion' => 'nullable|string|max:255',
            'estado' => 'required|in:activo,inactivo,pendiente', // Usar el ENUM
            'notas' => 'nullable|string',
        ]);
        
        try {
            // 2. **ACTUALIZACIÓN**: 
            $cliente->update($request->all());

            // 3. **REDIRECCIÓN CON ÉXITO**
            return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
        } catch (\Exception $e) {
             Log::error("Error al actualizar cliente: " . $e->getMessage());
             return back()->withInput()->with('error', 'Error al actualizar el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Elimina el cliente especificado de la base de datos.
     */
    public function destroy(string $id)
    {
        try {
            // 1. **BÚSQUEDA Y ELIMINACIÓN**
            $cliente = ClienteModelo::findOrFail($id);
            $cliente->delete();

            // 2. **REDIRECCIÓN CON ÉXITO**
            return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar cliente: " . $e->getMessage());
            return back()->with('error', 'Error al eliminar el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Devuelve las incidencias asociadas a un cliente en formato JSON.
     */
    public function incidencias(ClienteModelo $cliente): JsonResponse
    {
        try {
            $prioridadMap = [
                'baja' => ['label' => 'Baja', 'class' => 'badge rounded-pill text-bg-success'],
                'media' => ['label' => 'Media', 'class' => 'badge rounded-pill text-bg-warning'],
                'alta' => ['label' => 'Alta', 'class' => 'badge rounded-pill text-bg-danger'],
                'critica' => ['label' => 'Crítica', 'class' => 'badge rounded-pill bg-danger text-white'],
            ];

            $estadoMap = [
                'abierta' => ['label' => 'Abierta', 'class' => 'badge rounded-pill text-bg-info'],
                'en_proceso' => ['label' => 'En proceso', 'class' => 'badge rounded-pill text-bg-primary'],
                'resuelta' => ['label' => 'Resuelta', 'class' => 'badge rounded-pill text-bg-success'],
                'cerrada' => ['label' => 'Cerrada', 'class' => 'badge rounded-pill text-bg-secondary'],
            ];

            $incidencias = $cliente->incidencias()
                ->with('responsable')
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($incidencia) use ($prioridadMap, $estadoMap) {
                    $prioridad = $prioridadMap[$incidencia->prioridad] ?? ['label' => ucfirst($incidencia->prioridad ?? 'N/A'), 'class' => 'badge rounded-pill bg-secondary-subtle text-secondary'];
                    $estado = $estadoMap[$incidencia->estado] ?? ['label' => ucfirst(str_replace('_', ' ', $incidencia->estado ?? 'N/A')), 'class' => 'badge rounded-pill bg-secondary-subtle text-secondary'];
                    $fechaReporte = $incidencia->fecha_reporte ? $incidencia->fecha_reporte->format('d/m/Y') : null;
                    $fechaCreacion = optional($incidencia->created_at)->format('d/m/Y H:i');

                    return [
                        'id' => $incidencia->id,
                        'titulo' => $incidencia->titulo,
                        'responsable' => optional($incidencia->responsable)->name,
                        'prioridad_label' => $prioridad['label'],
                        'prioridad_class' => $prioridad['class'],
                        'estado_label' => $estado['label'],
                        'estado_class' => $estado['class'],
                        'fecha' => $fechaReporte ?? $fechaCreacion,
                        'show_url' => route('incidencias.show', $incidencia),
                    ];
                });

            return response()->json([
                'cliente' => [
                    'id' => $cliente->id,
                    'nombre' => $cliente->nombre,
                ],
                'incidencias' => $incidencias,
            ]);
        } catch (\Exception $e) {
            Log::error("Error al obtener incidencias de cliente {$cliente->id}: " . $e->getMessage());

            return response()->json([
                'message' => 'No se pudieron obtener las incidencias del cliente.'
            ], 500);
        }
    }

    /**
     * Devuelve las facturas asociadas a un cliente en formato JSON.
     */
    public function facturas(ClienteModelo $cliente): JsonResponse
    {
        try {
            $estadoMap = [
                'pendiente' => ['label' => 'Pendiente', 'class' => 'badge rounded-pill text-bg-warning'],
                'pagada' => ['label' => 'Pagada', 'class' => 'badge rounded-pill text-bg-success'],
                'vencida' => ['label' => 'Vencida', 'class' => 'badge rounded-pill text-bg-danger'],
                'cancelada' => ['label' => 'Cancelada', 'class' => 'badge rounded-pill text-bg-secondary'],
            ];

            $facturas = $cliente->facturas()
                ->orderByDesc('fecha_emision')
                ->get()
                ->map(function ($factura) use ($estadoMap) {
                    $estado = $estadoMap[$factura->estado] ?? [
                        'label' => ucfirst(str_replace('_', ' ', $factura->estado ?? 'N/A')),
                        'class' => 'badge rounded-pill bg-secondary-subtle text-secondary',
                    ];

                    $referencia = $factura->numero_factura
                        ?? $factura->numero
                        ?? sprintf('FACT-%04d', $factura->id);

                    return [
                        'id' => $factura->id,
                        'referencia' => $referencia,
                        'fecha_emision' => optional($factura->fecha_emision)->format('d/m/Y'),
                        'total_formatted' => number_format((float) ($factura->total ?? 0), 2, ',', '.') . ' EUR',
                        'estado_label' => $estado['label'],
                        'estado_class' => $estado['class'],
                        'view_url' => route('facturas.show', $factura),
                        'download_url' => route('facturas.pdf', $factura),
                    ];
                });

            return response()->json([
                'cliente' => [
                    'id' => $cliente->id,
                    'nombre' => $cliente->nombre,
                ],
                'facturas' => $facturas,
            ]);
        } catch (\Exception $e) {
            Log::error("Error al obtener facturas de cliente {$cliente->id}: " . $e->getMessage());

            return response()->json([
                'message' => 'No se pudieron obtener las facturas del cliente.'
            ], 500);
        }
    }
}
