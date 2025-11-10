<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de la Incidencia | CRM Sistema</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('crm-favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-5xl bg-white rounded-xl shadow-2xl p-6 md:p-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3">Detalle de la Incidencia</h1>

        <a href="{{ route('incidencias.index') }}" class="text-indigo-600 hover:text-indigo-800 inline-block font-medium transition duration-150">
            &larr; Volver al Listado de Incidencias
        </a>

        @php
            $estadoBadgeClass = match($incidencia->estado) {
                'abierta' => 'bg-sky-100 text-sky-700',
                'en_proceso' => 'bg-indigo-100 text-indigo-700',
                'resuelta' => 'bg-emerald-100 text-emerald-700',
                'cerrada' => 'bg-gray-200 text-gray-800',
                default => 'bg-gray-100 text-gray-700'
            };

            $prioridadBadgeClass = match($incidencia->prioridad) {
                'baja' => 'bg-emerald-100 text-emerald-700',
                'media' => 'bg-amber-100 text-amber-700',
                'alta' => 'bg-orange-100 text-orange-700',
                'critica' => 'bg-red-100 text-red-700',
                default => 'bg-gray-100 text-gray-700'
            };
        @endphp

        <div class="mt-4 mb-8 flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-widest text-gray-500">Título</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $incidencia->titulo }}</p>
            </div>

            <div class="flex flex-wrap gap-4">
                <div>
                    <p class="text-xs uppercase tracking-widest text-gray-500">Estado Actual</p>
                    <span class="mt-2 inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $estadoBadgeClass }}">
                        {{ $estadosEtiqueta[$incidencia->estado] ?? ucfirst(str_replace('_', ' ', $incidencia->estado)) }}
                    </span>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-widest text-gray-500">Prioridad</p>
                    <span class="mt-2 inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $prioridadBadgeClass }}">
                        {{ $prioridadesEtiqueta[$incidencia->prioridad] ?? ucfirst($incidencia->prioridad) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                <p class="text-xs uppercase tracking-widest text-gray-500">Cliente</p>
                <p class="mt-2 text-lg font-semibold text-gray-900">
                    {{ $incidencia->cliente?->nombre ?? 'Sin cliente asignado' }}
                </p>
                <p class="mt-1 text-sm text-gray-600">
                    {{ $incidencia->cliente?->email ?? 'Sin correo registrado' }}
                </p>
                <p class="mt-1 text-sm text-gray-600">
                    {{ $incidencia->cliente?->telefono ?? 'Sin teléfono registrado' }}
                </p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                <p class="text-xs uppercase tracking-widest text-gray-500">Responsable</p>
                <p class="mt-2 text-lg font-semibold text-gray-900">
                    {{ $incidencia->responsable?->name ?? 'Sin asignar' }}
                </p>
                <p class="mt-1 text-sm text-gray-600">
                    {{ $incidencia->responsable?->email ?? 'Sin correo disponible' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs uppercase tracking-widest text-gray-500 mb-1">Creada</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $incidencia->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs uppercase tracking-widest text-gray-500 mb-1">Actualizada</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $incidencia->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs uppercase tracking-widest text-gray-500 mb-1">Fecha de Resolución</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $incidencia->fecha_resolucion?->format('d/m/Y') ?? 'Sin resolver' }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Descripción</h3>
                <p class="mt-2 whitespace-pre-line rounded-lg border border-gray-200 bg-gray-50 p-4 text-gray-700">
                    {{ $incidencia->descripcion }}
                </p>
            </div>

            @if ($incidencia->solucion)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Solución Propuesta</h3>
                    <p class="mt-2 whitespace-pre-line rounded-lg border border-emerald-100 bg-emerald-50 p-4 text-emerald-800">
                        {{ $incidencia->solucion }}
                    </p>
                </div>
            @endif
        </div>

        <div class="mt-10 flex flex-wrap items-center justify-end gap-3">
            <a
                href="{{ route('incidencias.edit', $incidencia) }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-orange-500 bg-orange-500 px-5 py-2.5 text-sm font-semibold uppercase tracking-wide text-white shadow-[0_6px_16px_rgba(249,115,22,0.35)] transition-transform duration-200 hover:scale-[1.03] focus:scale-[1.03] focus:outline-none focus:ring-2 focus:ring-orange-300 focus:ring-offset-2"
            >
                Editar
            </a>

            <form
                action="{{ route('incidencias.destroy', $incidencia) }}"
                method="POST"
                id="deleteIncidenciaForm"
            >
                @csrf
                @method('DELETE')
                <button
                    type="button"
                    id="openDeleteModal"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-red-500 bg-red-500 px-5 py-2.5 text-sm font-semibold uppercase tracking-wide text-white shadow-[0_6px_16px_rgba(239,68,68,0.35)] transition-transform duration-200 hover:scale-[1.03] focus:scale-[1.03] focus:outline-none focus:ring-2 focus:ring-red-300 focus:ring-offset-2"
                >
                    Eliminar
                </button>
            </form>
        </div>

        <div
            id="deleteIncidenciaModal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 px-4"
            aria-hidden="true"
        >
            <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-2xl">
                <h2 class="text-xl font-semibold text-slate-900">¿Eliminar esta incidencia?</h2>
                <p class="mt-3 text-sm text-slate-600">
                    Esta acción no se puede deshacer. Se eliminará toda la información asociada a la incidencia.
                </p>

                <div class="mt-8 flex flex-wrap justify-end gap-3">
                    <button
                        type="button"
                        id="cancelDeleteModal"
                        class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition-colors duration-150 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        id="confirmDeleteModal"
                        class="inline-flex items-center justify-center rounded-lg bg-red-500 px-4 py-2 text-sm font-semibold text-white shadow-[0_6px_16px_rgba(239,68,68,0.35)] transition-transform duration-150 hover:scale-[1.02] focus:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-red-300 focus:ring-offset-2"
                    >
                        Eliminar incidencia
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('deleteIncidenciaModal');
            const openButton = document.getElementById('openDeleteModal');
            const cancelButton = document.getElementById('cancelDeleteModal');
            const confirmButton = document.getElementById('confirmDeleteModal');
            const form = document.getElementById('deleteIncidenciaForm');

            if (!modal || !openButton || !cancelButton || !confirmButton || !form) {
                return;
            }

            const openModal = () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            };

            openButton.addEventListener('click', openModal);
            cancelButton.addEventListener('click', closeModal);

            confirmButton.addEventListener('click', () => {
                form.submit();
            });

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>
</body>
</html>