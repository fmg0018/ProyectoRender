<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente | CRM Sistema</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('crm-favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl bg-white rounded-xl shadow-2xl p-6 md:p-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-4">Editar Cliente:</h1>

        <!-- Mensaje de errores de validación -->
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

        <a href="{{ route('clientes.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 mb-4 inline-block">
            ← Volver al Listado de Clientes
        </a>

        <!-- Formulario de Edición -->
       
        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- GRUPO DE CAMPOS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- Nombre Completo -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre Completo *</label>
                    <input type="text" name="nombre" id="nombre" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ old('nombre', $cliente->nombre) }}">
                </div>

                <!-- Correo Electrónico -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
                    <input type="email" name="email" id="email" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ old('email', $cliente->email) }}">
                </div>
            </div>

            <!-- GRUPO DE CAMPOS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono *</label>
                    <input type="text" name="telefono" id="telefono" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ old('telefono', $cliente->telefono) }}">
                </div>

                <!-- Ciudad -->
                <div>
                    <label for="ciudad" class="block text-sm font-medium text-gray-700">Ciudad</label>
                    <input type="text" name="ciudad" id="ciudad"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ old('ciudad', $cliente->ciudad) }}">
                </div>
            </div>

            <!-- País -->
            <div>
                <label for="pais" class="block text-sm font-medium text-gray-700">País</label>
                <input type="text" name="pais" id="pais"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500"
                       value="{{ old('pais', $cliente->pais) }}">
            </div>

            <!-- Dirección Completa -->
            <div>
                <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección Completa</label>
                <textarea name="direccion" id="direccion" rows="3"
                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500">
                    {{ old('direccion', $cliente->direccion) }}
                </textarea>
            </div>
            
            <!-- Campo de ESTADO -->
          
            <input type="hidden" name="estado" value="{{ old('estado', $cliente->estado) }}">


            <!-- Botón de Actualización -->
            <div class="pt-4">
                <button type="submit"
                        class="w-full py-3 px-6 border border-transparent rounded-lg shadow-lg text-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    Actualizar Cliente
                </button>
            </div>
        </form>

    </div>
</body>
</html>
