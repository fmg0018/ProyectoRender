<?php

namespace App\Http\Controllers;

use App\Models\ClienteModelo;
use App\Models\IncidenciaModelo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class IncidenciaControlador extends Controller
{
    public function index(): View
    {
        $incidencias = IncidenciaModelo::with(['cliente', 'responsable'])
            ->latest('created_at')
            ->paginate(12)
            ->withQueryString();

        $prioridadesEtiqueta = [
            'baja' => __('Baja'),
            'media' => __('Media'),
            'alta' => __('Alta'),
            'critica' => __('Crítica'),
        ];

        $estadosEtiqueta = [
            'abierta' => __('Abierta'),
            'resuelta' => __('Resuelta'),
        ];

        return view('incidencias.incidenciasVistaIndex', compact(
            'incidencias',
            'prioridadesEtiqueta',
            'estadosEtiqueta'
        ));
    }

    public function create(): View
    {
        $clientes = ClienteModelo::orderBy('nombre')->get();
        $usuarios = User::orderBy('name')->get();

        $prioridades = [
            'baja' => __('Baja'),
            'media' => __('Media'),
            'alta' => __('Alta'),
            'critica' => __('Crítica'),
        ];

        $estados = [
            'abierta' => __('Abierta'),
            'resuelta' => __('Resuelta'),
        ];

        return view('incidencias.incidenciasVistaCreate', compact(
            'clientes',
            'usuarios',
            'prioridades',
            'estados'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'cliente_id' => ['required', 'exists:clientes,id'],
            'user_id' => ['required', 'exists:users,id'],
            'prioridad' => ['required', 'in:baja,media,alta,critica'],
            'estado' => ['required', 'in:abierta,resuelta'],
            'solucion' => ['nullable', 'string'],
        ]);

        $data['fecha_reporte'] = Carbon::today()->toDateString();
        $data['fecha_resolucion'] = $data['estado'] === 'resuelta'
            ? Carbon::today()->toDateString()
            : null;

        IncidenciaModelo::create($data);

        return redirect()
            ->route('incidencias.index')
            ->with('status', __('Incidencia creada correctamente.'));
    }

    public function show(IncidenciaModelo $incidencia): View
    {
        $incidencia->load(['cliente', 'responsable']);

        $prioridadesEtiqueta = [
            'baja' => __('Baja'),
            'media' => __('Media'),
            'alta' => __('Alta'),
            'critica' => __('Crítica'),
        ];

        $estadosEtiqueta = [
            'abierta' => __('Abierta'),
            'resuelta' => __('Resuelta'),
        ];

        return view('incidencias.incidenciasVistaShow', compact(
            'incidencia',
            'prioridadesEtiqueta',
            'estadosEtiqueta'
        ));
    }

    public function edit(IncidenciaModelo $incidencia): View
    {
        $clientes = ClienteModelo::orderBy('nombre')->get();
        $usuarios = User::orderBy('name')->get();

        $prioridades = [
            'baja' => __('Baja'),
            'media' => __('Media'),
            'alta' => __('Alta'),
            'critica' => __('Crítica'),
        ];

        $estados = [
            'abierta' => __('Abierta'),
            'resuelta' => __('Resuelta'),
        ];

        return view('incidencias.incidenciasVistaEdit', compact(
            'incidencia',
            'clientes',
            'usuarios',
            'prioridades',
            'estados'
        ));
    }

    public function update(Request $request, IncidenciaModelo $incidencia): RedirectResponse
    {
        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'cliente_id' => ['required', 'exists:clientes,id'],
            'user_id' => ['required', 'exists:users,id'],
            'prioridad' => ['required', 'in:baja,media,alta,critica'],
            'estado' => ['required', 'in:abierta,resuelta'],
            'solucion' => ['nullable', 'string'],
        ]);

        $data['fecha_reporte'] = $incidencia->fecha_reporte ?? Carbon::today()->toDateString();
        $data['fecha_resolucion'] = $data['estado'] === 'resuelta'
            ? ($incidencia->fecha_resolucion ?? Carbon::today()->toDateString())
            : null;

        $incidencia->update($data);

        return redirect()
            ->route('incidencias.index')
            ->with('status', __('Incidencia actualizada correctamente.'));
    }

    public function destroy(IncidenciaModelo $incidencia): RedirectResponse
    {
        $incidencia->delete();

        return redirect()
            ->route('incidencias.index')
            ->with('status', __('Incidencia eliminada correctamente.'));
    }
}
