{{-- Vista Index de Clientes --}}
@extends('layouts.app')

@section('title', 'Clientes')

@push('head')
<style>
    .clientes-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.5rem 0;
    }

    .clientes-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid rgba(148, 163, 184, 0.2);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
        padding: 2rem;
    }

    .clientes-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.9);
        margin-bottom: 1.5rem;
    }

    .clientes-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .clientes-create-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.75rem 1.4rem;
        border-radius: 12px;
        font-weight: 600;
        background: linear-gradient(135deg, #6366f1, #4338ca);
        color: #ffffff !important;
        border: none;
        box-shadow: 0 15px 30px rgba(99, 102, 241, 0.28);
        text-decoration: none;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .clientes-create-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 36px rgba(67, 56, 202, 0.32);
        color: #ffffff;
    }

    .clientes-alert-error {
        border-radius: 14px;
        padding: 1rem 1.2rem;
        font-size: 0.95rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(248, 113, 113, 0.45);
        background: rgba(254, 226, 226, 0.45);
        color: #b91c1c;
    }

    .clientes-empty {
        padding: 2.5rem 1rem;
        text-align: center;
        color: #6b7280;
        font-size: 0.98rem;
        border-radius: 14px;
        border: 1px dashed rgba(148, 163, 184, 0.4);
        background: rgba(248, 250, 252, 0.7);
    }

    .clientes-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .clientes-table thead {
        background: #f8fafc;
    }

    .clientes-table thead th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
        padding: 0.9rem 1rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.9);
    }

    .clientes-table tbody tr {
        transition: background 0.18s ease;
    }

    .clientes-table tbody tr:hover {
        background: #f9fbff;
    }

    .clientes-table tbody td {
        padding: 1rem;
        font-size: 0.95rem;
        color: #1f2937;
        border-bottom: 1px solid rgba(226, 232, 240, 0.7);
        vertical-align: middle;
    }

    .clientes-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.4rem 0.85rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-estado-activo {
        background: rgba(34, 197, 94, 0.18);
        color: #15803d;
    }

    .badge-estado-inactivo {
        background: rgba(248, 113, 113, 0.2);
        color: #b91c1c;
    }

    .badge-estado-pendiente {
        background: rgba(251, 191, 36, 0.2);
        color: #b45309;
    }

    .badge-estado-neutral {
        background: rgba(148, 163, 184, 0.18);
        color: #475569;
    }

    .clientes-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
    }

    .clientes-action-btn {
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

    .clientes-action-btn svg {
        width: 20px;
        height: 20px;
    }

    .clientes-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 18px rgba(15, 23, 42, 0.18);
    }

    .clientes-action-btn.view-facturas {
        color: #059669;
        border-color: rgba(16, 185, 129, 0.28);
    }

    .clientes-action-btn.view-incidencias {
        color: #d97706;
        border-color: rgba(249, 115, 22, 0.28);
    }

    .clientes-action-btn.edit {
        color: #4338ca;
        border-color: rgba(99, 102, 241, 0.35);
    }

    .clientes-action-btn.delete {
        color: #dc2626;
        border-color: rgba(248, 113, 113, 0.35);
        background: rgba(254, 226, 226, 0.45);
    }

    .clientes-pagination {
        margin-top: 1.5rem;
    }

    .cliente-incidencias-empty {
        border-radius: 14px;
        border: 1px dashed rgba(148, 163, 184, 0.45);
        background: rgba(248, 250, 252, 0.85);
        padding: 1.5rem;
        color: #64748b;
        font-size: 0.95rem;
    }

    .clientes-incidencias-modal {
        border-radius: 20px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
    }

    .clientes-incidencias-modal .modal-header {
        border-bottom: none;
    }

    .cliente-incidencias-modal-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #0f172a;
    }

    .clientes-facturas-modal {
        border-radius: 20px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
    }

    .clientes-facturas-modal .modal-header {
        border-bottom: none;
    }

    .cliente-facturas-modal-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #0f172a;
    }

    .cliente-facturas-empty {
        border-radius: 14px;
        border: 1px dashed rgba(148, 163, 184, 0.45);
        background: rgba(248, 250, 252, 0.85);
        padding: 1.5rem;
        color: #64748b;
        font-size: 0.95rem;
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
        .clientes-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .clientes-create-btn {
            width: 100%;
            justify-content: center;
        }

        .clientes-table thead {
            display: none;
        }

        .clientes-table tbody td {
            display: block;
            padding: 0.75rem 0.6rem;
        }

        .clientes-table tbody tr {
            display: block;
            padding: 0.85rem 0;
        }

        .clientes-actions {
            justify-content: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="clientes-wrapper">
    <div class="clientes-card">
        <div class="clientes-header">
            <h1 class="clientes-title">Listado de Clientes</h1>
            <a href="{{ route('clientes.create') }}" class="clientes-create-btn">
                <span>+</span>
                Nuevo Cliente
            </a>
        </div>

        @if (session('error'))
            <div class="clientes-alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if ($clientes->isEmpty())
            <p class="clientes-empty">¡Aún no hay clientes registrados!</p>
        @else
            <div class="table-responsive">
                <table class="clientes-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td>{{ $cliente->nombre }}</td>
                                <td>{{ $cliente->email ?? 'Sin email' }}</td>
                                <td>{{ $cliente->telefono ?? 'Sin teléfono' }}</td>
                                <td class="text-center">
                                    @php
                                        $estado = $cliente->estado ?? 'neutral';
                                        $badgeClass = match($estado) {
                                            'activo' => 'badge-estado-activo',
                                            'inactivo' => 'badge-estado-inactivo',
                                            'pendiente' => 'badge-estado-pendiente',
                                            default => 'badge-estado-neutral',
                                        };
                                    @endphp
                                    <span class="clientes-badge {{ $badgeClass }}">
                                        {{ ucfirst($estado) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="clientes-actions">
                                        <button
                                            type="button"
                                            class="clientes-action-btn view-facturas"
                                            title="Ver facturas"
                                            data-cliente-id="{{ $cliente->id }}"
                                            data-cliente-nombre="{{ $cliente->nombre }}"
                                            data-facturas-url="{{ route('clientes.facturas', $cliente) }}"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 9V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2" />
                                                <path d="M9 17h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2Z" />
                                                <path d="M15 12a2 2 0 1 1-4 0" />
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            class="clientes-action-btn view-incidencias"
                                            title="Ver incidencias"
                                            data-cliente-id="{{ $cliente->id }}"
                                            data-cliente-nombre="{{ $cliente->nombre }}"
                                            data-incidencias-url="{{ route('clientes.incidencias', $cliente) }}"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 9v2" />
                                                <path d="M12 15h.01" />
                                                <path d="M5.062 19H18.94c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.332 16c-.77 1.333.192 3 1.73 3Z" />
                                            </svg>
                                        </button>
                                        <a href="{{ route('clientes.edit', $cliente->id) }}" class="clientes-action-btn edit" title="Editar cliente">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M16.862 4.487a2.121 2.121 0 0 1 3 3L8.25 19.1a2.25 2.25 0 0 1-.948.565l-3.003.86.86-3.003a2.25 2.25 0 0 1 .565-.948L16.862 4.487Z" />
                                                <path d="M19.5 7.125 16.875 4.5" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="d-inline-block clientes-delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="button"
                                                class="clientes-action-btn delete"
                                                title="Eliminar cliente"
                                                data-delete-cliente
                                                data-cliente-nombre="{{ $cliente->nombre }}"
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

            <div class="clientes-pagination">
                {{ $clientes->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal facturas por cliente -->
<div class="modal fade" id="clienteFacturasModal" tabindex="-1" aria-labelledby="clienteFacturasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 clientes-facturas-modal">
            <div class="modal-header">
                <h5 class="modal-title cliente-facturas-modal-title" id="clienteFacturasModalLabel">Facturas del cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="cliente-facturas-loader d-flex d-none justify-content-center align-items-center w-100 py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando facturas...</span>
                    </div>
                </div>

                <div class="alert alert-danger cliente-facturas-error d-none" role="alert"></div>

                <div class="cliente-facturas-empty d-none text-center">
                    No se encontraron facturas registradas para este cliente.
                </div>

                <div class="table-responsive cliente-facturas-table-wrapper d-none">
                    <table class="table table-hover align-middle cliente-facturas-table">
                        <thead class="table-light">
                            <tr>
                                <th>Referencia</th>
                                <th>Fecha de factura</th>
                                <th>Total final</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal incidencias por cliente -->
<div class="modal fade" id="clienteIncidenciasModal" tabindex="-1" aria-labelledby="clienteIncidenciasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 clientes-incidencias-modal">
            <div class="modal-header">
                <h5 class="modal-title cliente-incidencias-modal-title" id="clienteIncidenciasModalLabel">Incidencias del cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="cliente-incidencias-loader d-flex d-none justify-content-center align-items-center w-100 py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando incidencias...</span>
                    </div>
                </div>

                <div class="alert alert-danger cliente-incidencias-error d-none" role="alert"></div>

                <div class="cliente-incidencias-empty d-none text-center">
                    No se encontraron incidencias registradas para este cliente.
                </div>

                <div class="table-responsive cliente-incidencias-table-wrapper d-none">
                    <table class="table table-hover align-middle cliente-incidencias-table">
                        <thead class="table-light">
                            <tr>
                                <th>Título</th>
                                <th>Responsable</th>
                                <th>Prioridad</th>
                                <th>Estado</th>
                                <th>Creada</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div
    id="clienteDeleteModal"
    class="delete-modal-backdrop"
    role="dialog"
    aria-modal="true"
    aria-labelledby="clienteDeleteModalTitle"
    aria-hidden="true"
>
    <div class="delete-modal-card">
        <h2 id="clienteDeleteModalTitle" class="delete-modal-title">¿Eliminar este cliente?</h2>
        <p class="delete-modal-text">
            Esta acción no se puede deshacer. Se eliminará toda la información asociada al cliente <span id="clienteDeleteName" class="fw-semibold text-dark"></span>.
        </p>
        <div class="delete-modal-actions">
            <button type="button" class="delete-modal-cancel" id="cancelDeleteCliente">Cancelar</button>
            <button type="button" class="delete-modal-confirm" id="confirmDeleteCliente">Eliminar cliente</button>
        </div>
    </div>
</div>
@endsection
{{-- Fin de la vista Index de Clientes --}}

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const hasBootstrap = typeof bootstrap !== 'undefined';

    const escapeHtml = (value) => {
        if (!value && value !== 0) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    };

    const setupFacturasModal = () => {
        if (!hasBootstrap) {
            return;
        }

        const facturasModalElement = document.getElementById('clienteFacturasModal');
        if (!facturasModalElement) {
            return;
        }

        const modalInstance = new bootstrap.Modal(facturasModalElement);
        const modalTitle = facturasModalElement.querySelector('.cliente-facturas-modal-title');
        const loader = facturasModalElement.querySelector('.cliente-facturas-loader');
        const tableWrapper = facturasModalElement.querySelector('.cliente-facturas-table-wrapper');
        const tableBody = facturasModalElement.querySelector('.cliente-facturas-table tbody');
        const emptyState = facturasModalElement.querySelector('.cliente-facturas-empty');
        const errorAlert = facturasModalElement.querySelector('.cliente-facturas-error');

        const resetState = () => {
            loader.classList.add('d-none');
            tableWrapper.classList.add('d-none');
            emptyState.classList.add('d-none');
            errorAlert.classList.add('d-none');
            tableBody.innerHTML = '';
        };

        facturasModalElement.addEventListener('hidden.bs.modal', resetState);

        document.querySelectorAll('.clientes-action-btn.view-facturas').forEach((button) => {
            button.addEventListener('click', () => {
                const facturasUrl = button.dataset.facturasUrl;
                if (!facturasUrl) {
                    return;
                }

               const clienteNombre = button.dataset.clienteNombre || '';
               const tituloHTML = clienteNombre 
               ? `Facturas de <span style="color: #1d4ed8;">${escapeHtml(clienteNombre)}</span>` 
               : 'Facturas del cliente';
               
               modalTitle.innerHTML = tituloHTML;
 


                resetState();
                loader.classList.remove('d-none');
                button.disabled = true;

                modalInstance.show();

                fetch(facturasUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('No se pudo cargar la información de facturas.');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        loader.classList.add('d-none');

                        const facturas = Array.isArray(data.facturas) ? data.facturas : [];

                        if (!facturas.length) {
                            emptyState.classList.remove('d-none');
                            return;
                        }

                        const rows = facturas.map((item) => `
                            <tr>
                                <td>${escapeHtml(item.referencia || '-')}</td>
                                <td>${escapeHtml(item.fecha_emision || '-')}</td>
                                <td>${escapeHtml(item.total_formatted || '0,00 EUR')}</td>
                                <td><span class="${escapeHtml(item.estado_class || 'badge rounded-pill bg-secondary text-white')}">${escapeHtml(item.estado_label || '-')}</span></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <a href="${escapeHtml(item.download_url || '#')}" 
                                        class="btn btn-sm btn-link" 
                                        title="Descargar factura" 
                                        target="_blank" 
                                        rel="noopener"
                                        style="color: #1d4ed8;">
                                            <i class="fas fa-download"></i> 
                                        </a>
                                        
                                        <a href="${escapeHtml(item.view_url || '#')}" 
                                        class="btn btn-sm btn-link" 
                                        title="Ver factura"
                                        style="color: #1d4ed8;">
                                            <i class="far fa-eye"></i> 
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        `).join('');

                        tableBody.innerHTML = rows;
                        tableWrapper.classList.remove('d-none');
                    })
                    .catch((error) => {
                        loader.classList.add('d-none');
                        errorAlert.textContent = error.message || 'Ocurrió un error al obtener las facturas.';
                        errorAlert.classList.remove('d-none');
                    })
                    .finally(() => {
                        button.disabled = false;
                    });
            });
        });
    };

    const setupIncidenciasModal = () => {
        if (!hasBootstrap) {
            return;
        }

        const incidenciasModalElement = document.getElementById('clienteIncidenciasModal');
        if (!incidenciasModalElement) {
            return;
        }

        const modalInstance = new bootstrap.Modal(incidenciasModalElement);
        const modalTitle = incidenciasModalElement.querySelector('.cliente-incidencias-modal-title');
        const loader = incidenciasModalElement.querySelector('.cliente-incidencias-loader');
        const tableWrapper = incidenciasModalElement.querySelector('.cliente-incidencias-table-wrapper');
        const tableBody = incidenciasModalElement.querySelector('.cliente-incidencias-table tbody');
        const emptyState = incidenciasModalElement.querySelector('.cliente-incidencias-empty');
        const errorAlert = incidenciasModalElement.querySelector('.cliente-incidencias-error');

        const resetState = () => {
            loader.classList.add('d-none');
            tableWrapper.classList.add('d-none');
            emptyState.classList.add('d-none');
            errorAlert.classList.add('d-none');
            tableBody.innerHTML = '';
        };

        incidenciasModalElement.addEventListener('hidden.bs.modal', resetState);

        document.querySelectorAll('.clientes-action-btn.view-incidencias').forEach((button) => {
            button.addEventListener('click', () => {
                const incidenciasUrl = button.dataset.incidenciasUrl;
                if (!incidenciasUrl) {
                    return;
                }

                const clienteNombre = button.dataset.clienteNombre || '';
                modalTitle.textContent = clienteNombre ? `Incidencias de ${clienteNombre}` : 'Incidencias del cliente';

                resetState();
                loader.classList.remove('d-none');
                button.disabled = true;

                modalInstance.show();

                fetch(incidenciasUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('No se pudo cargar la información de incidencias.');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        loader.classList.add('d-none');

                        const incidencias = Array.isArray(data.incidencias) ? data.incidencias : [];

                        if (!incidencias.length) {
                            emptyState.classList.remove('d-none');
                            return;
                        }

                        const rows = incidencias.map((item) => `
                            <tr>
                                <td>${escapeHtml(item.titulo || '-')}</td>
                                <td>${escapeHtml(item.responsable || 'Sin asignar')}</td>
                                <td><span class="${escapeHtml(item.prioridad_class || 'badge rounded-pill bg-secondary text-white')}">${escapeHtml(item.prioridad_label || '-')}</span></td>
                                <td><span class="${escapeHtml(item.estado_class || 'badge rounded-pill bg-secondary text-white')}">${escapeHtml(item.estado_label || '-')}</span></td>
                                <td>${escapeHtml(item.fecha || '-')}</td>
                                <td class="text-center">
                                    <a href="${escapeHtml(item.show_url || '#')}" class="btn btn-sm btn-outline-primary" title="Ver incidencia">
                                        <i class="fas fa-arrow-up-right-from-square"></i>
                                    </a>
                                </td>
                            </tr>
                        `).join('');

                        tableBody.innerHTML = rows;
                        tableWrapper.classList.remove('d-none');
                    })
                    .catch((error) => {
                        loader.classList.add('d-none');
                        errorAlert.textContent = error.message || 'Ocurrió un error al obtener las incidencias.';
                        errorAlert.classList.remove('d-none');
                    })
                    .finally(() => {
                        button.disabled = false;
                    });
            });
        });
    };

    const setupDeleteModal = () => {
        const deleteModal = document.getElementById('clienteDeleteModal');
        const cancelDeleteButton = document.getElementById('cancelDeleteCliente');
        const confirmDeleteButton = document.getElementById('confirmDeleteCliente');
        const deleteNameTarget = document.getElementById('clienteDeleteName');
        let deleteFormToSubmit = null;

        if (!deleteModal || !cancelDeleteButton || !confirmDeleteButton) {
            return;
        }

        const openDeleteModal = () => {
            deleteModal.classList.add('active');
            deleteModal.setAttribute('aria-hidden', 'false');
        };

        const closeDeleteModal = () => {
            deleteModal.classList.remove('active');
            deleteModal.setAttribute('aria-hidden', 'true');
            deleteFormToSubmit = null;
            if (deleteNameTarget) {
                deleteNameTarget.textContent = '';
            }
        };

        document.querySelectorAll('[data-delete-cliente]').forEach((button) => {
            button.addEventListener('click', () => {
                const form = button.closest('form');
                if (!form) {
                    return;
                }

                deleteFormToSubmit = form;
                if (deleteNameTarget) {
                    deleteNameTarget.textContent = button.dataset.clienteNombre || '';
                }
                openDeleteModal();
            });
        });

        cancelDeleteButton.addEventListener('click', closeDeleteModal);

        confirmDeleteButton.addEventListener('click', () => {
            if (deleteFormToSubmit) {
                deleteFormToSubmit.submit();
            }
        });

        deleteModal.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && deleteModal.classList.contains('active')) {
                closeDeleteModal();
            }
        });
    };

    setupFacturasModal();
    setupIncidenciasModal();
    setupDeleteModal();
});
</script>
@endpush
