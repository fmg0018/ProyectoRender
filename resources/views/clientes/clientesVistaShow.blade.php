<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Cliente | CRM Sistema</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('crm-favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-start justify-center p-4 sm:p-8">
    <div class="w-full max-w-xl bg-white rounded-xl shadow-2xl p-6 md:p-8 mt-4">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h1 class="text-3xl font-bold text-gray-800">Detalles del Cliente</h1>
            <!-- Botón para Editar -->
            <a href="{{ route('clientes.edit', $cliente) }}" class="px-4 py-2 bg-yellow-500 text-white font-semibold rounded-lg shadow-md hover:bg-yellow-600 transition duration-300 text-sm">
                Editar
            </a>
        </div>

        <!-- Enlace de regreso al listado -->
        <a href="{{ route('clientes.index') }}" class="text-indigo-600 hover:text-indigo-800 mb-6 inline-block font-medium transition duration-150">
            &larr; Volver al Listado
        </a>

        <!-- Información del Cliente en formato de lista de descripción -->
        <dl class="divide-y divide-gray-200">
            <!-- ID -->
            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">ID de Cliente</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $cliente->id }}</dd>
            </div>
            <!-- Nombre -->
            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Nombre Completo</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">{{ $cliente->name }}</dd>
            </div>
            <!-- Email -->
            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="mt-1 text-sm text-blue-600 sm:mt-0 sm:col-span-2 hover:text-blue-800"><a href="mailto:{{ $cliente->email }}">{{ $cliente->email }}</a></dd>
            </div>
            <!-- Teléfono -->
            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $cliente->phone ?? 'N/A' }}</dd>
            </div>
            <!-- Ciudad -->
            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Ciudad</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $cliente->city ?? 'N/A' }}</dd>
            </div>
            <!-- País -->
            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">País</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $cliente->country ?? 'N/A' }}</dd>
            </div>
            <!-- Dirección -->
            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $cliente->address ?? 'No especificada' }}</dd>
            </div>
            <!-- Creado en -->
            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $cliente->created_at->format('d/m/Y H:i') }}</dd>
            </div>
        </dl>

        <!-- Botón de Eliminación (Requiere confirmación) -->
        <div class="mt-8 pt-4 border-t border-gray-200">
            <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" onsubmit="return confirm('¿CONFIRMAR ELIMINACIÓN?\nEsta acción es irreversible y eliminará a {{ $cliente->name }} permanentemente.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-2 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 transition duration-300">
                    Eliminar Cliente
                </button>
            </form>
        </div>
    </div>
</body>
</html>
