<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nueva Factura | CRM Sistema</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('crm-favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Pequeño ajuste para la tabla de líneas */
        .table-fixed thead th { text-align: left; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-3xl bg-white rounded-xl shadow-2xl p-6 md:p-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3">Registrar Nueva Factura</h1>

        <a href="{{ route('facturas.index') }}" class="text-indigo-600 hover:text-indigo-800 mb-4 inline-block font-medium transition duration-150">
            &larr; Volver al Listado de Facturas
        </a>

        @php
            $formDisabled = isset($clientes) ? $clientes->isEmpty() : false;
        @endphp

        @if (isset($clientes) && $clientes->isEmpty())
            <div class="mb-6 rounded-md border border-amber-300 bg-amber-50 p-4 text-sm text-amber-900">
                No hay clientes registrados todavía. Crea un cliente antes de registrar facturas.
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-5">
                <strong class="font-bold">¡Ups!</strong> Hubo algunos problemas con los datos ingresados:
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('facturas.store') }}" method="POST" class="space-y-6" @if($formDisabled) disabled @endif>
            @csrf

            <fieldset class="space-y-6">
                <div>
                    <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente <span class="text-red-500">*</span></label>
                    <select id="cliente_id" name="cliente_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                        <option value="">Selecciona un cliente</option>
                        @if(isset($clientes))
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>{{ $cliente->nombre }} @if($cliente->email) <span class="text-sm text-gray-400">({{ $cliente->email }})</span>@endif</option>
                            @endforeach
                        @endif
                    </select>
                    @error('cliente_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="fecha_emision" class="block text-sm font-medium text-gray-700 mb-1">Fecha de emisión</label>
                        <input type="date" name="fecha_emision" id="fecha_emision" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('fecha_emision') }}">
                    </div>
                    <div>
                        <label for="fecha_vencimiento" class="block text-sm font-medium text-gray-700 mb-1">Fecha de vencimiento</label>
                        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('fecha_vencimiento') }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="subtotal" class="block text-sm font-medium text-gray-700 mb-1">Subtotal (€)</label>
                        <input type="number" step="0.01" name="subtotal" id="subtotal" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('subtotal') }}">
                    </div>
                    <div>
                        <label for="impuestos" class="block text-sm font-medium text-gray-700 mb-1">Impuesto (%)</label>
                        <input type="number" step="0.01" name="impuestos" id="impuestos" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('impuestos', 0) }}">
                    </div>
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Opcional">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select id="estado" name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white">
                        <option value="pendiente" {{ old('estado')=='pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="pagada" {{ old('estado')=='pagada' ? 'selected' : '' }}>Pagada</option>
                        <option value="vencida" {{ old('estado')=='vencida' ? 'selected' : '' }}>Vencida</option>
                        <option value="cancelada" {{ old('estado')=='cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Líneas</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full table-fixed border" id="lineasTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2">Descripción</th>
                                    <th class="px-3 py-2">Cantidad</th>
                                    <th class="px-3 py-2">Precio unitario</th>
                                    <th class="px-3 py-2">Impuesto (%)</th>
                                    <th class="px-3 py-2"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <!-- Filas añadidas por JS -->
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <button type="button" id="addLinea" class="inline-flex items-center px-3 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Añadir línea</button>
                    </div>
                </div>

            </fieldset>

            <div>
                <button type="submit" class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-200 transition duration-300">Crear factura</button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function(){
        // Manejo de líneas (similar a la implementación previa)
        const table = document.getElementById('lineasTable');
        const addBtn = document.getElementById('addLinea');

        function createCell(inner){
            const td = document.createElement('td');
            td.className = 'px-3 py-2 align-top';
            td.innerHTML = inner;
            return td;
        }

        function addRow(data = {}){
            const tr = document.createElement('tr');
            tr.appendChild(createCell(`<input name="lineas[][descripcion]" class="w-full px-3 py-2 border border-gray-300 rounded" value="${(data.descripcion||'')}">`));
            tr.appendChild(createCell(`<input name="lineas[][cantidad]" type="number" class="w-full px-3 py-2 border border-gray-300 rounded" value="${(data.cantidad||1)}">`));
            tr.appendChild(createCell(`<input name="lineas[][precio_unitario]" type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded" value="${(data.precio_unitario||'0.00')}">`));
            tr.appendChild(createCell(`<input name="lineas[][impuesto]" type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded" value="${(data.impuesto||'0.00')}">`));
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'px-2 py-1 bg-red-500 text-white rounded removeLinea hover:bg-red-600';
            btn.textContent = 'Eliminar';
            const tdBtn = document.createElement('td');
            tdBtn.className = 'px-3 py-2';
            tdBtn.appendChild(btn);
            tr.appendChild(tdBtn);
            table.querySelector('tbody').appendChild(tr);
        }

        addBtn.addEventListener('click', function(){ addRow(); });

        table.addEventListener('click', function(e){
            if(e.target.classList.contains('removeLinea')){
                e.target.closest('tr').remove();
            }
        });
    });
    </script>
</body>
</html>
