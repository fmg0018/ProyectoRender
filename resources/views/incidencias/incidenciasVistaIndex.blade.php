{{-- Vista Index de Incidencias --}}
@extends('layouts.app')

@section('title', 'Incidencias')

@push('head')
<style>
    .incidencias-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.5rem 0;
    }

    .incidencias-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid rgba(148, 163, 184, 0.2);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
        padding: 2rem;
    }

    .incidencias-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.9);
        margin-bottom: 1.5rem;
    }

    .incidencias-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .incidencias-create-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.75rem 1.4rem;
        border-radius: 12px;
        font-weight: 600;
        background: linear-gradient(135deg, #f97316, #ea580c);
        color: #ffffff !important;
        border: none;
        box-shadow: 0 15px 30px rgba(234, 88, 12, 0.28);
        text-decoration: none;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .incidencias-create-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 36px rgba(234, 88, 12, 0.32);
        color: #ffffff;
    }

    .incidencias-alert {
        border-radius: 14px;
        padding: 1rem 1.2rem;
        font-size: 0.95rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(16, 185, 129, 0.4);
        background: rgba(16, 185, 129, 0.12);
        color: #047857;
    }

    .incidencias-empty {
        padding: 2.5rem 1rem;
        text-align: center;
        color: #6b7280;
        font-size: 0.98rem;
        border-radius: 14px;
        border: 1px dashed rgba(148, 163, 184, 0.4);
        background: rgba(248, 250, 252, 0.7);
    }

    .incidencias-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .incidencias-table thead {
        background: #f8fafc;
    }

    .incidencias-table thead th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
        padding: 0.9rem 1rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.9);
    }

    .incidencias-table tbody tr {
        transition: background 0.18s ease;
    }

    .incidencias-table tbody tr:hover {
        background: #f9fbff;
    }

    .incidencias-table tbody td {
        padding: 1rem;
        font-size: 0.95rem;
        color: #1f2937;
        border-bottom: 1px solid rgba(226, 232, 240, 0.7);
        vertical-align: middle;
    }

    .incidencias-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.4rem 0.85rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-prioridad-baja {
        background: rgba(16, 185, 129, 0.16);
        color: #047857;
    }

    .badge-prioridad-media {
        background: rgba(245, 158, 11, 0.18);
        color: #b45309;
    }

    .badge-prioridad-alta {
        background: rgba(249, 115, 22, 0.18);
        color: #c2410c;
    }

    .badge-prioridad-critica {
        background: rgba(239, 68, 68, 0.22);
        color: #b91c1c;
    }

    .badge-estado-abierta {
        background: rgba(14, 165, 233, 0.16);
        color: #0369a1;
    }

    .badge-estado-en_proceso {
        background: rgba(99, 102, 241, 0.16);
        color: #3730a3;
    }

    .badge-estado-resuelta {
        background: rgba(34, 197, 94, 0.18);
        color: #166534;
    }

    .badge-estado-cerrada {
        background: rgba(100, 116, 139, 0.18);
        color: #334155;
    }

    .badge-neutral {
        background: rgba(148, 163, 184, 0.16);
        color: #475569;
    }

    .incidencias-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
    }

    .incidencias-action-btn {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(148, 163, 184, 0.35);
        background: #ffffff;
        color: #475569;
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease, color 0.18s ease;
        text-decoration: none;
    }

    .incidencias-action-btn svg {
        width: 20px;
        height: 20px;
    }

    .incidencias-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 18px rgba(15, 23, 42, 0.18);
    }

    .incidencias-action-btn.view {
        color: #2563eb;
        border-color: rgba(37, 99, 235, 0.28);
    }

    .incidencias-action-btn.edit {
        color: #0f172a;
        border-color: rgba(148, 163, 184, 0.45);
    }

    .incidencias-action-btn.delete {
        color: #dc2626;
        border-color: rgba(248, 113, 113, 0.35);
        background: rgba(254, 226, 226, 0.45);
    }

    .incidencias-pagination {
        margin-top: 1.5rem;
    }

    .delete-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.65);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        z-index: 2000;
    }

    .delete-modal-backdrop.active {
        display: flex;
    }

    .delete-modal-card {
        width: 100%;
        max-width: 420px;
        background: #ffffff;
        border-radius: 18px;
        padding: 2rem;
        box-shadow: 0 24px 48px rgba(15, 23, 42, 0.25);
    }

    .delete-modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.75rem;
    }

    .delete-modal-text {
        font-size: 0.95rem;
        color: #475569;
        margin-bottom: 1.75rem;
    }

    .delete-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .delete-modal-cancel,
    .delete-modal-confirm {
        border-radius: 12px;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
        border: none;
    }

    .delete-modal-cancel {
        background: #ffffff;
        border: 1px solid rgba(148, 163, 184, 0.6);
        color: #475569;
    }

    .delete-modal-cancel:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 22px rgba(15, 23, 42, 0.14);
    }

    .delete-modal-confirm {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #ffffff;
        box-shadow: 0 16px 32px rgba(239, 68, 68, 0.28);
    }

    .delete-modal-confirm:hover {
        transform: translateY(-1px);
        box-shadow: 0 18px 36px rgba(239, 68, 68, 0.32);
    }

    @media (max-width: 768px) {
        .incidencias-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .incidencias-create-btn {
            width: 100%;
            justify-content: center;
        }

        .incidencias-table thead {
            display: none;
        }

        .incidencias-table tbody td {
            display: block;
            padding: 0.75rem 0.6rem;
        }

        .incidencias-table tbody tr {
            display: block;
            padding: 0.85rem 0;
        }

        .incidencias-actions {
            justify-content: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="incidencias-wrapper">
    <div class="incidencias-card">
        <div class="incidencias-header">
            <h1 class="incidencias-title">Listado de Incidencias</h1>
            <a href="{{ route('incidencias.create') }}" class="incidencias-create-btn">
                <span>+</span>
                Nueva Incidencia
            </a>
        </div>

        @if (session('status'))
            <div class="incidencias-alert">
                {{ session('status') }}
            </div>
        @endif

        @php
            $prioridadesEtiqueta = $prioridadesEtiqueta ?? [];
            $estadosEtiqueta = $estadosEtiqueta ?? [];

            $prioridadClases = [
                'baja' => 'badge-prioridad-baja',
                'media' => 'badge-prioridad-media',
                'alta' => 'badge-prioridad-alta',
                'critica' => 'badge-prioridad-critica',
            ];

            $estadoClases = [
                'abierta' => 'badge-estado-abierta',
                'en_proceso' => 'badge-estado-en_proceso',
                'resuelta' => 'badge-estado-resuelta',
                'cerrada' => 'badge-estado-cerrada',
            ];
        @endphp

        @if ($incidencias->isEmpty())
            <p class="incidencias-empty">
                Todavía no hay incidencias registradas. Crea una para comenzar a hacer seguimiento.
            </p>
        @else
            <div class="table-responsive">
                <table class="incidencias-table">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Cliente</th>
                            <th>Responsable</th>
                            <th>Prioridad</th>
                            <th>Estado</th>
                            <th>Creada</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incidencias as $incidencia)
                            @php
                                $prioridadTexto = $prioridadesEtiqueta[$incidencia->prioridad] ?? ucfirst($incidencia->prioridad);
                                $estadoTexto = $estadosEtiqueta[$incidencia->estado] ?? ucfirst(str_replace('_', ' ', $incidencia->estado));
                            @endphp
                            <tr>
                                <td>{{ $incidencia->titulo }}</td>
                                <td>{{ optional($incidencia->cliente)->nombre ?? 'Sin cliente' }}</td>
                                <td>{{ optional($incidencia->responsable)->name ?? 'Sin asignar' }}</td>
                                <td>
                                    <span class="incidencias-badge {{ $prioridadClases[$incidencia->prioridad] ?? 'badge-neutral' }}">
                                        {{ $prioridadTexto }}
                                    </span>
                                </td>
                                <td>
                                    <span class="incidencias-badge {{ $estadoClases[$incidencia->estado] ?? 'badge-neutral' }}">
                                        {{ $estadoTexto }}
                                    </span>
                                </td>
                                <td>{{ $incidencia->created_at?->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="incidencias-actions">
                                        <a
                                            href="{{ route('incidencias.show', $incidencia) }}"
                                            class="incidencias-action-btn view"
                                            title="Ver incidencia"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12Z" />
                                                <circle cx="12" cy="12" r="2.25" />
                                            </svg>
                                        </a>

                                        <a
                                            href="{{ route('incidencias.edit', $incidencia) }}"
                                            class="incidencias-action-btn edit"
                                            title="Editar incidencia"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M16.862 4.487a2.121 2.121 0 0 1 3 3L8.25 19.1a2.25 2.25 0 0 1-.948.565l-3.003.86.86-3.003a2.25 2.25 0 0 1 .565-.948L16.862 4.487Z" />
                                                <path d="M19.5 7.125 16.875 4.5" />
                                            </svg>
                                        </a>

                                        <form
                                            action="{{ route('incidencias.destroy', $incidencia) }}"
                                            method="POST"
                                            class="d-inline-block incidencias-delete-form"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="button"
                                                class="incidencias-action-btn delete"
                                                title="Eliminar incidencia"
                                                data-delete-incidencia
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3.75 5.25h16.5" />
                                                    <path d="M10.5 9.75v6" />
                                                    <path d="M13.5 9.75v6" />
                                                    <path d="M5.25 5.25 6 19.5a2.25 2.25 0 0 0 2.244 2.1h7.512A2.25 2.25 0 0 0 18 19.5l.75-14.25" />
                                                    <path d="M9 5.25V3.75A1.5 1.5 0 0 1 10.5 2.25h3A1.5 1.5 0 0 1 15 3.75v1.5" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="incidencias-pagination">
                {{ $incidencias->links() }}
            </div>
        @endif
    </div>
</div>

<div
    id="incidenciaDeleteModal"
    class="delete-modal-backdrop"
    role="dialog"
    aria-modal="true"
    aria-labelledby="incidenciaDeleteModalTitle"
    aria-hidden="true"
>
    <div class="delete-modal-card">
        <h2 id="incidenciaDeleteModalTitle" class="delete-modal-title">¿Eliminar esta incidencia?</h2>
        <p class="delete-modal-text">Esta acción no se puede deshacer. La incidencia y su historial asociado se eliminarán definitivamente.</p>
        <div class="delete-modal-actions">
            <button type="button" class="delete-modal-cancel" id="cancelDeleteIncidencia">Cancelar</button>
            <button type="button" class="delete-modal-confirm" id="confirmDeleteIncidencia">Eliminar incidencia</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('incidenciaDeleteModal');
        const cancelButton = document.getElementById('cancelDeleteIncidencia');
        const confirmButton = document.getElementById('confirmDeleteIncidencia');
        let formToSubmit = null;

        if (!modal || !cancelButton || !confirmButton) {
            return;
        }

        const openModal = () => {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
        };

        const closeModal = () => {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            formToSubmit = null;
        };

        document.querySelectorAll('[data-delete-incidencia]').forEach((button) => {
            button.addEventListener('click', () => {
                const form = button.closest('form');
                if (!form) {
                    return;
                }

                formToSubmit = form;
                openModal();
            });
        });

        cancelButton.addEventListener('click', closeModal);

        confirmButton.addEventListener('click', () => {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });
    });
</script>
@endpush

{{-- Fin de la vista Index de Incidencias --}}