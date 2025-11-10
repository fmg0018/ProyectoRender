{{-- Vista Index de Facturas (estilo igual al de Incidencias) --}}
@extends('layouts.app')

@section('title', 'Facturas')

@push('head')
<style>
    .facturas-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.5rem 0;
    }

    .facturas-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid rgba(148, 163, 184, 0.2);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
        padding: 2rem;
    }

    .facturas-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.9);
        margin-bottom: 1.5rem;
    }

    .facturas-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .facturas-create-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.75rem 1.4rem;
        border-radius: 12px;
        font-weight: 600;
        background: linear-gradient(135deg, #22c55e, #15803d);
        color: #ffffff !important;
        border: none;
        box-shadow: 0 15px 30px rgba(34, 197, 94, 0.28);
        text-decoration: none;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .facturas-create-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 36px rgba(34, 197, 94, 0.32);
        color: #ffffff;
    }

    .facturas-empty {
        padding: 2.5rem 1rem;
        text-align: center;
        color: #6b7280;
        font-size: 0.98rem;
        border-radius: 14px;
        border: 1px dashed rgba(148, 163, 184, 0.4);
        background: rgba(248, 250, 252, 0.7);
    }

    .facturas-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .facturas-table thead {
        background: #f8fafc;
    }

    .facturas-table thead th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
        padding: 0.9rem 1rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.9);
    }

    .facturas-table tbody tr {
        transition: background 0.18s ease;
    }

    .facturas-table tbody tr:hover {
        background: #f9fbff;
    }

    .facturas-table tbody td {
        padding: 1rem;
        font-size: 0.95rem;
        color: #1f2937;
        border-bottom: 1px solid rgba(226, 232, 240, 0.7);
        vertical-align: middle;
    }

    .facturas-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.4rem 0.85rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-estado-pagada { background: rgba(34, 197, 94, 0.18); color: #166534; }
    .badge-estado-vencida { background: rgba(239, 68, 68, 0.22); color: #b91c1c; }
    .badge-estado-cancelada { background: rgba(100, 116, 139, 0.18); color: #334155; }
    .badge-neutral { background: rgba(148, 163, 184, 0.16); color: #475569; }

    .facturas-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
    }

    .facturas-action-btn {
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

    .facturas-action-btn svg { width: 20px; height: 20px; }

    .facturas-action-btn.view { color: #2563eb; border-color: rgba(37, 99, 235, 0.28); }
    .facturas-action-btn.edit { color: #0f172a; border-color: rgba(148, 163, 184, 0.45); }
    .facturas-action-btn.delete { color: #dc2626; border-color: rgba(248, 113, 113, 0.35); background: rgba(254,226,226,0.45); }

    .facturas-pagination { margin-top: 1.5rem; }

    .delete-modal-backdrop { position: fixed; inset: 0; background: rgba(15,23,42,0.65); display: none; align-items: center; justify-content: center; padding: 1.5rem; z-index:2000; }
    .delete-modal-backdrop.active { display:flex; }
    .delete-modal-card { width:100%; max-width:420px; background:#fff; border-radius:18px; padding:2rem; box-shadow:0 24px 48px rgba(15,23,42,0.25); }
    .delete-modal-title{ font-size:1.25rem; font-weight:600; color:#0f172a; margin-bottom:0.75rem; }
    .delete-modal-text{ font-size:0.95rem; color:#475569; margin-bottom:1.75rem; }
    .delete-modal-actions{ display:flex; justify-content:flex-end; gap:0.75rem; }
    .delete-modal-cancel, .delete-modal-confirm { border-radius:12px; padding:0.6rem 1.2rem; font-weight:600; font-size:0.9rem; transition:transform .18s, box-shadow .18s; border:none; }
    .delete-modal-cancel{ background:#fff; border:1px solid rgba(148,163,184,0.6); color:#475569 }
    .delete-modal-confirm{ background: linear-gradient(135deg,#ef4444,#dc2626); color:#fff; box-shadow:0 16px 32px rgba(239,68,68,0.28); }

    @media (max-width:768px){
        .facturas-header{ flex-direction:column; gap:1rem; text-align:center }
        .facturas-create-btn{ width:100%; justify-content:center }
        .facturas-table thead{ display:none }
        .facturas-table tbody td{ display:block; padding:0.75rem 0.6rem }
        .facturas-table tbody tr{ display:block; padding:0.85rem 0 }
        .facturas-actions{ justify-content:flex-start }
    }
</style>
@endpush

@section('content')
<div class="facturas-wrapper">
    <div class="facturas-card">
        <div class="facturas-header">
            <h1 class="facturas-title">Listado de Facturas</h1>
            <a href="{{ route('facturas.create') }}" class="facturas-create-btn">
                <span>+</span>
                Nueva Factura
            </a>
        </div>

        @if (session('status'))
            <div class="facturas-alert">
                {{ session('status') }}
            </div>
        @endif

        @if ($facturas->isEmpty())
            <p class="facturas-empty">Todavía no hay facturas registradas. Crea una para comenzar.</p>
        @else
            <div class="table-responsive">
                <table class="facturas-table">
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Vencimiento</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($facturas as $factura)
                            @php
                                $estado = $factura->estado ?? 'pendiente';
                                $estadosClases = [
                                    'pagada' => 'badge-estado-pagada',
                                    'vencida' => 'badge-estado-vencida',
                                    'cancelada' => 'badge-estado-cancelada',
                                ];
                                $estadoTexto = ucfirst(str_replace('_',' ', $estado));
                            @endphp
                            <tr>
                                <td>{{ $factura->numero_factura }}</td>
                                <td>{{ optional($factura->cliente)->nombre ?? 'Sin cliente' }}</td>
                                <td>{{ $factura->fecha_emision?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ number_format($factura->total,2,',','.') }} €</td>
                                <td>
                                    <span class="facturas-badge {{ $estadosClases[$estado] ?? 'badge-neutral' }}">{{ $estadoTexto }}</span>
                                </td>
                                <td>{{ $factura->fecha_vencimiento?->format('d/m/Y') ?? '-' }}</td>
                                <td>
                                    <div class="facturas-actions">
                                        <a href="{{ route('facturas.show', $factura) }}" class="facturas-action-btn view" title="Ver factura">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12Z"/><circle cx="12" cy="12" r="2.25"/></svg>
                                        </a>

                                        <a href="{{ route('facturas.edit', $factura) }}" class="facturas-action-btn edit" title="Editar factura">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16.862 4.487a2.121 2.121 0 0 1 3 3L8.25 19.1a2.25 2.25 0 0 1-.948.565l-3.003.86.86-3.003a2.25 2.25 0 0 1 .565-.948L16.862 4.487Z"/><path d="M19.5 7.125 16.875 4.5"/></svg>
                                        </a>

                                        <form action="{{ route('facturas.destroy', $factura) }}" method="POST" class="d-inline-block facturas-delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="facturas-action-btn delete" title="Eliminar factura" data-delete-factura>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3.75 5.25h16.5"/><path d="M10.5 9.75v6"/><path d="M13.5 9.75v6"/><path d="M5.25 5.25 6 19.5a2.25 2.25 0 0 0 2.244 2.1h7.512A2.25 2.25 0 0 0 18 19.5l.75-14.25"/><path d="M9 5.25V3.75A1.5 1.5 0 0 1 10.5 2.25h3A1.5 1.5 0 0 1 15 3.75v1.5"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="facturas-pagination">
                {{ $facturas->links() }}
            </div>
        @endif
    </div>
</div>

<div id="facturaDeleteModal" class="delete-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="facturaDeleteModalTitle" aria-hidden="true">
    <div class="delete-modal-card">
        <h2 id="facturaDeleteModalTitle" class="delete-modal-title">¿Eliminar esta factura?</h2>
        <p class="delete-modal-text">Esta acción no se puede deshacer. La factura y su historial asociado se eliminarán definitivamente.</p>
        <div class="delete-modal-actions">
            <button type="button" class="delete-modal-cancel" id="cancelDeleteFactura">Cancelar</button>
            <button type="button" class="delete-modal-confirm" id="confirmDeleteFactura">Eliminar factura</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('facturaDeleteModal');
        const cancelButton = document.getElementById('cancelDeleteFactura');
        const confirmButton = document.getElementById('confirmDeleteFactura');
        let formToSubmit = null;

        if (!modal || !cancelButton || !confirmButton) return;

        const openModal = () => { modal.classList.add('active'); modal.setAttribute('aria-hidden','false'); };
        const closeModal = () => { modal.classList.remove('active'); modal.setAttribute('aria-hidden','true'); formToSubmit = null; };

        document.querySelectorAll('[data-delete-factura]').forEach((button) => {
            button.addEventListener('click', () => {
                const form = button.closest('form');
                if (!form) return;
                formToSubmit = form;
                openModal();
            });
        });

        cancelButton.addEventListener('click', closeModal);
        confirmButton.addEventListener('click', () => { if (formToSubmit) formToSubmit.submit(); });

        modal.addEventListener('click', (event) => { if (event.target === modal) closeModal(); });
        document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && modal.classList.contains('active')) closeModal(); });
    });
</script>
@endpush

{{-- Fin de la vista Index de Facturas (parecido a Incidencias) --}}
