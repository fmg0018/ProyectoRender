{{-- Vista Show de Facturas --}}
@extends('layouts.app')

@section('title', 'Detalle de Factura')

@section('content')
<div>
    <div class="modal fade" id="facturaCreadaModal" tabindex="-1" aria-labelledby="facturaCreadaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        <h5 class="modal-title text-success fw-bold" id="facturaCreadaModalLabel">Factura creada correctamente</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body pt-3">
                    <div class="bg-light border rounded-3 p-4 mb-4">
                        <h4 class="fw-bold mb-1">
                            Factura
                            <span class="text-secondary fw-normal">#{{ $factura->numero_factura }}</span>
                        </h4>
                        <div class="text-secondary small mb-0">Guardada el {{ $factura->created_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="text-uppercase text-secondary small fw-semibold mb-3">Datos del cliente</h6>
                                <p class="mb-2"><strong>Cliente:</strong> {{ $factura->cliente->nombre ?? '-' }}</p>
                                <p class="mb-2"><strong>Email:</strong> {{ $factura->cliente->email ?? '-' }}</p>
                                <p class="mb-0"><strong>Estado:</strong>
                                    @php
                                        $badgeClass = match($factura->estado) {
                                            'pagada' => 'badge bg-success',
                                            'vencida' => 'badge bg-danger',
                                            'cancelada' => 'badge bg-secondary',
                                            default => 'badge bg-warning text-dark'
                                        };
                                    @endphp
                                    <span class="{{ $badgeClass }}">{{ ucfirst($factura->estado) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="text-uppercase text-secondary small fw-semibold mb-3">Fechas clave</h6>
                                <p class="mb-2"><strong>Fecha emisión:</strong> {{ $factura->fecha_emision->format('d/m/Y') }}</p>
                                <p class="mb-0"><strong>Fecha vencimiento:</strong> {{ $factura->fecha_vencimiento->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 text-center h-100">
                                <h6 class="text-uppercase text-secondary small fw-semibold mb-2">Subtotal</h6>
                                <p class="fs-5 fw-bold mb-0">{{ number_format($factura->subtotal, 2, ',', '.') }} &euro;</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 text-center h-100">
                                <h6 class="text-uppercase text-secondary small fw-semibold mb-2">Impuestos</h6>
                                <p class="fs-5 fw-bold mb-0">{{ number_format($factura->impuestos, 2, ',', '.') }} &euro;</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 text-center h-100 bg-success-subtle">
                                <h6 class="text-uppercase text-secondary small fw-semibold mb-2">Total</h6>
                                <p class="fs-4 fw-bold mb-0 text-success">{{ number_format($factura->total, 2, ',', '.') }} &euro;</p>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded-3 p-3">
                        <h6 class="text-uppercase text-secondary small fw-semibold mb-3">Descripción</h6>
                        <p class="mb-0">{{ $factura->descripcion }}</p>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <div class="d-flex flex-wrap w-100 justify-content-between align-items-center gap-2">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('facturas.pdf', $factura->id) }}" class="btn btn-outline-primary">
                                <i class="bi bi-download me-1"></i>
                                Descargar PDF
                            </a>
                            @if((auth()->user()->role ?? '') === 'admin')
                                <button id="btnEnviar" class="btn btn-success" data-id="{{ $factura->id }}">
                                    <i class="bi bi-envelope-fill me-1"></i>
                                    Enviar por email
                                </button>
                            @endif
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('facturas.index') }}" class="btn btn-secondary">Volver al listado</a>
                            <a href="{{ route('facturas.edit', $factura->id) }}" class="btn btn-primary">Editar factura</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal confirmación envío -->
    <div class="modal fade" id="confirmEnviarModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirmar envío</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            ¿Desea enviar esta factura por email al cliente <strong>{{ $factura->cliente->nombre ?? '-' }}</strong> (<em>{{ $factura->cliente->email ?? 'sin email' }}</em>)?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" id="confirmEnviarBtn" class="btn btn-primary">Enviar</button>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const facturaModalEl = document.getElementById('facturaCreadaModal');
        if (!facturaModalEl) return;

        const facturaModal = new bootstrap.Modal(facturaModalEl);
        let redirectOnHide = true;

        facturaModalEl.addEventListener('hidden.bs.modal', () => {
            if (redirectOnHide) {
                window.location.href = "{{ route('facturas.index') }}";
            }
        });

        facturaModal.show();

        const btnEnviar = document.getElementById('btnEnviar');
        const confirmModalEl = document.getElementById('confirmEnviarModal');
        const confirmEnviarBtn = document.getElementById('confirmEnviarBtn');

        if (btnEnviar && confirmModalEl && confirmEnviarBtn) {
            const confirmModal = new bootstrap.Modal(confirmModalEl);

            btnEnviar.addEventListener('click', () => {
                redirectOnHide = false;
                facturaModal.hide();
                confirmModal.show();
            });

            confirmModalEl.addEventListener('hidden.bs.modal', () => {
                if (!redirectOnHide) {
                    redirectOnHide = true;
                    facturaModal.show();
                }
            });

            confirmEnviarBtn.addEventListener('click', async () => {
                const id = btnEnviar.getAttribute('data-id');
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    const res = await fetch(`/facturas/${id}/enviar`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({})
                    });

                    if (res.ok) {
                        redirectOnHide = true;
                        confirmModal.hide();
                        location.reload();
                    } else {
                        alert('Error al enviar.');
                    }
                } catch (e) {
                    alert('Error de red.');
                }
            });
        }
    })();
</script>
@endpush