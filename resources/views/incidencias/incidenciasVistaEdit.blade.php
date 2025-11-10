<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Incidencia | CRM Sistema</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('crm-favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-3xl bg-white rounded-xl shadow-2xl p-6 md:p-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3">Editar Incidencia</h1>

        <a href="{{ route('incidencias.index') }}" class="text-indigo-600 hover:text-indigo-800 mb-4 inline-block font-medium transition duration-150">
            &larr; Volver al Listado de Incidencias
        </a>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Creada</p>
                <p class="text-sm font-semibold text-gray-800">{{ $incidencia->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Última Actualización</p>
                <p class="text-sm font-semibold text-gray-800">{{ $incidencia->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Fecha de Resolución</p>
                <p class="text-sm font-semibold text-gray-800">{{ $incidencia->fecha_resolucion?->format('d/m/Y') ?? 'Sin resolver' }}</p>
            </div>
        </div>

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

        <form method="POST" action="{{ route('incidencias.update', $incidencia) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="titulo"
                    name="titulo"
                    value="{{ old('titulo', $incidencia->titulo) }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 transition duration-150"
                    placeholder="Describe brevemente la incidencia"
                >
                @error('titulo')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción <span class="text-red-500">*</span></label>
                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="5"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 transition duration-150"
                    placeholder="Incluye todos los detalles relevantes de la incidencia"
                >{{ old('descripcion', $incidencia->descripcion) }}</textarea>
                @error('descripcion')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente Asociado <span class="text-red-500">*</span></label>
                    <select
                        id="cliente_id"
                        name="cliente_id"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:ring-orange-500 focus:border-orange-500 transition duration-150"
                    >
                        @foreach ($clientes as $cliente)
                            <option
                                value="{{ $cliente->id }}"
                                {{ (int) old('cliente_id', $incidencia->cliente_id) === $cliente->id ? 'selected' : '' }}
                            >
                                {{ $cliente->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('cliente_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Responsable <span class="text-red-500">*</span></label>
                    <select
                        id="user_id"
                        name="user_id"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:ring-orange-500 focus:border-orange-500 transition duration-150"
                    >
                        @foreach ($usuarios as $usuario)
                            <option
                                value="{{ $usuario->id }}"
                                {{ (int) old('user_id', $incidencia->user_id) === $usuario->id ? 'selected' : '' }}
                            >
                                {{ $usuario->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="prioridad" class="block text-sm font-medium text-gray-700 mb-1">Prioridad <span class="text-red-500">*</span></label>
                    <select
                        id="prioridad"
                        name="prioridad"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:ring-orange-500 focus:border-orange-500 transition duration-150"
                    >
                        @foreach ($prioridades as $valor => $etiqueta)
                            <option value="{{ $valor }}" {{ old('prioridad', $incidencia->prioridad) === $valor ? 'selected' : '' }}>
                                {{ $etiqueta }}
                            </option>
                        @endforeach
                    </select>
                    @error('prioridad')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado <span class="text-red-500">*</span></label>
                    <select
                        id="estado"
                        name="estado"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:ring-orange-500 focus:border-orange-500 transition duration-150"
                    >
                        <option value="abierta" {{ old('estado', $incidencia->estado) === 'abierta' ? 'selected' : '' }}>Abierta</option>
                        <option value="resuelta" {{ old('estado', $incidencia->estado) === 'resuelta' ? 'selected' : '' }}>Resuelta</option>
                    </select>
                    @error('estado')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="solucion" class="block text-sm font-medium text-gray-700 mb-1">Solución Propuesta (opcional)</label>
                <textarea
                    id="solucion"
                    name="solucion"
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 transition duration-150"
                    placeholder="Describe la solución aplicada o planificada, si procede"
                >{{ old('solucion', $incidencia->solucion) }}</textarea>
                @error('solucion')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button
                    type="submit"
                    class="w-full py-3 bg-orange-500 text-white font-semibold rounded-lg shadow-md hover:bg-orange-600 focus:outline-none focus:ring-4 focus:ring-orange-200 transition duration-300 transform hover:scale-[1.01]"
                >
                    Actualizar Incidencia
                </button>
            </div>
        </form>
    </div>
</body>
</html>