<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Factura | CRM Sistema</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('crm-favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-3xl bg-white rounded-xl shadow-2xl p-6 md:p-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3 flex flex-col sm:flex-row sm:items-end sm:gap-3">
            <span>Editar Factura</span>
            <span class="text-base font-semibold text-gray-500">#{{ $factura->numero_factura }}</span>
        </h1>

        <a href="{{ route('facturas.index') }}" class="text-indigo-600 hover:text-indigo-800 mb-1.5 inline-block font-medium transition duration-150">
            &larr; Volver al Listado de Facturas
        </a>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                <strong class="font-bold">¡Ups!</strong>
                <span class="block sm:inline">Hubo algunos problemas con los datos ingresados:</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('facturas.update', $factura->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <section class="space-y-6">
                <div>
                    <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-0">Cliente</label>
                    <select
                        id="cliente_id"
                        name="cliente_id"
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg bg-white focus:ring-indigo-500 focus:border-indigo-500 transition"
                    >
                        <option value="">Selecciona un cliente</option>
                        @foreach ($clientes as $cliente)
                            <option
                                value="{{ $cliente->id }}"
                                data-email="{{ e($cliente->email ?? '') }}"
                                data-nombre="{{ e($cliente->nombre) }}"
                                {{ (int) old('cliente_id', $factura->cliente_id) === $cliente->id ? 'selected' : '' }}
                            >
                                {{ $cliente->nombre }}
                                @if($cliente->email)
                                    ({{ $cliente->email }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="cliente_name" id="cliente_name_hidden" value="{{ old('cliente_name', $factura->cliente->nombre ?? '') }}">

                    <div class="mt-3">
                        <label for="cliente_email" class="block text-sm font-medium text-gray-700 mb-1">Email del cliente</label>
                        <input
                            id="cliente_email"
                            name="cliente_email"
                            value="{{ old('cliente_email', $factura->cliente->email ?? '') }}"
                            readonly
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        >
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Selecciona un cliente para rellenar sus datos automáticamente.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="fecha_emision" class="block text-sm font-medium text-gray-700 mb-1">Fecha de emisión</label>
                        <input
                            type="date"
                            name="fecha_emision"
                            id="fecha_emision"
                            value="{{ old('fecha_emision', $factura->fecha_emision->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>
                    <div>
                        <label for="fecha_vencimiento" class="block text-sm font-medium text-gray-700 mb-1">Fecha de vencimiento</label>
                        <input
                            type="date"
                            name="fecha_vencimiento"
                            id="fecha_vencimiento"
                            value="{{ old('fecha_vencimiento', $factura->fecha_vencimiento->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="subtotal" class="block text-sm font-medium text-gray-700 mb-1">Subtotal (€)</label>
                        <input
                            type="number"
                            step="0.01"
                            name="subtotal"
                            id="subtotal"
                            value="{{ old('subtotal', $factura->subtotal) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>
                    <div>
                        <label for="impuestos" class="block text-sm font-medium text-gray-700 mb-1">Impuesto (%)</label>
                        @php
                            $impuestosDisplay = old('impuestos');
                            if (is_null($impuestosDisplay)) {
                                if ($factura->subtotal > 0) {
                                    $impuestosDisplay = round(($factura->impuestos / $factura->subtotal) * 100, 2);
                                } else {
                                    $impuestosDisplay = $factura->impuestos;
                                }
                            }
                        @endphp
                        <input
                            type="number"
                            step="0.01"
                            name="impuestos"
                            id="impuestos"
                            value="{{ $impuestosDisplay }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">{{ old('descripcion', $factura->descripcion) }}</textarea>
                </div>

                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select
                        name="estado"
                        id="estado"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="pendiente" {{ old('estado', $factura->estado) === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="pagada" {{ old('estado', $factura->estado) === 'pagada' ? 'selected' : '' }}>Pagada</option>
                        <option value="vencida" {{ old('estado', $factura->estado) === 'vencida' ? 'selected' : '' }}>Vencida</option>
                        <option value="cancelada" {{ old('estado', $factura->estado) === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-semibold text-gray-800">Líneas</h2>
                        <button
                            type="button"
                            id="addLinea"
                            class="inline-flex items-center px-3 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition"
                        >Añadir línea</button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-200 rounded-lg" id="lineasTable">
                            <thead class="bg-gray-50 text-sm text-gray-600">
                                <tr>
                                    <th class="px-3 py-2 text-left">Descripción</th>
                                    <th class="px-3 py-2 text-left">Cantidad</th>
                                    <th class="px-3 py-2 text-left">Precio unitario</th>
                                    <th class="px-3 py-2 text-left">Impuesto (%)</th>
                                    <th class="px-3 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($factura->lineas as $linea)
                                    @php
                                        $lineSubtotal = $linea->cantidad * $linea->precio_unitario;
                                        $lineImpuestoMon = $linea->total_linea - $lineSubtotal;
                                        $lineImpuestoPercent = $lineSubtotal > 0
                                            ? round(($lineImpuestoMon / $lineSubtotal) * 100, 2)
                                            : $linea->impuesto;
                                    @endphp
                                    <tr class="border-t border-gray-200">
                                        <td class="px-3 py-2">
                                            <input name="lineas[][descripcion]" class="w-full px-3 py-2 border border-gray-300 rounded" value="{{ $linea->descripcion }}">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input name="lineas[][cantidad]" type="number" class="w-full px-3 py-2 border border-gray-300 rounded" value="{{ $linea->cantidad }}">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input name="lineas[][precio_unitario]" type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded" value="{{ $linea->precio_unitario }}">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input name="lineas[][impuesto]" type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded" value="{{ $lineImpuestoPercent }}">
                                        </td>
                                        <td class="px-3 py-2 text-right">
                                            <button type="button" class="px-3 py-2 bg-red-500 text-white rounded removeLinea hover:bg-red-600 transition">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <div class="pt-2">
                <button
                    type="submit"
                    class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-200 transition"
                >
                    Actualizar factura
                </button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const clienteSelect = document.getElementById('cliente_id');
        const clienteEmailInput = document.getElementById('cliente_email');
        const clienteNameHidden = document.getElementById('cliente_name_hidden');

        const syncClienteData = () => {
            if (!clienteSelect) return;
            const option = clienteSelect.options[clienteSelect.selectedIndex];
            const email = option ? option.getAttribute('data-email') || '' : '';
            const nombre = option ? option.getAttribute('data-nombre') || '' : '';

            if (clienteEmailInput) {
                clienteEmailInput.value = email;
            }
            if (clienteNameHidden) {
                clienteNameHidden.value = nombre;
            }
        };

        if (clienteSelect) {
            clienteSelect.addEventListener('change', syncClienteData);
            syncClienteData();
        }

        const table = document.getElementById('lineasTable');
        const addBtn = document.getElementById('addLinea');

        const createCell = (content) => {
            const td = document.createElement('td');
            td.className = 'px-3 py-2';
            td.innerHTML = content;
            return td;
        };

        const addRow = (data = {}) => {
            const tr = document.createElement('tr');
            tr.className = 'border-t border-gray-200';
            tr.appendChild(createCell(`<input name="lineas[][descripcion]" class="w-full px-3 py-2 border border-gray-300 rounded" value="${data.descripcion ?? ''}">`));
            tr.appendChild(createCell(`<input name="lineas[][cantidad]" type="number" class="w-full px-3 py-2 border border-gray-300 rounded" value="${data.cantidad ?? 1}">`));
            tr.appendChild(createCell(`<input name="lineas[][precio_unitario]" type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded" value="${data.precio_unitario ?? '0.00'}">`));
            tr.appendChild(createCell(`<input name="lineas[][impuesto]" type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded" value="${data.impuesto ?? '0.00'}">`));

            const actionsTd = document.createElement('td');
            actionsTd.className = 'px-3 py-2 text-right';
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'px-3 py-2 bg-red-500 text-white rounded removeLinea hover:bg-red-600 transition';
            removeBtn.textContent = 'Eliminar';
            actionsTd.appendChild(removeBtn);
            tr.appendChild(actionsTd);

            table.querySelector('tbody').appendChild(tr);
        };

        addBtn.addEventListener('click', () => addRow());

        table.addEventListener('click', (event) => {
            if (event.target.classList.contains('removeLinea')) {
                event.target.closest('tr').remove();
            }
        });
    });
    </script>
</body>
</html>
