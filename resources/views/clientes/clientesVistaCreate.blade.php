<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Cliente | CRM Sistema</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('crm-favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl bg-white rounded-xl shadow-2xl p-6 md:p-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3">Registrar Nuevo Cliente</h1>

        <!-- Enlace de regreso al listado -->
        <a href="{{ route('clientes.index') }}" class="text-indigo-600 hover:text-indigo-800 mb-4 inline-block font-medium transition duration-150">
            &larr; Volver al Listado de Clientes
        </a>

        <!-- Formulario de Creación -->
        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf

            <!-- Validación de Errores (Mostrar si existen) -->
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre-->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                           placeholder="Ej: Javier Pérez">
                </div>

                <!-- Email-->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                           placeholder="Ej: contacto@ejemplo.com">
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono <span class="text-red-500">*</span></label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                           placeholder="Ej: +34 600 123 456">
                </div>

                <!-- Ciudad-->
                <div>
                    <label for="ciudad" class="block text-sm font-medium text-gray-700 mb-1">Ciudad</label>
                    <input type="text" name="ciudad" id="ciudad" value="{{ old('ciudad') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                           placeholder="Ej: Madrid">
                </div>
            </div>

            <!-- Dirección y País (Sección separada) -->
            <div class="mt-6 space-y-6">
                <!-- País -->
                <div>
                    <label for="pais" class="block text-sm font-medium text-gray-700 mb-1">País</label>
                    <input type="text" name="pais" id="pais" value="{{ old('pais') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                           placeholder="Ej: España">
                </div>
                
                <!-- Dirección-->
                <div>
                    <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección Completa</label>
                    <textarea name="direccion" id="direccion" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                              placeholder="Ej: Calle de la Ilusión, 12, 3º Izq">{{ old('direccion') }}</textarea>
                </div>
                
                <!--Empresa-->
                <div>
                    <label for="empresa" class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
                    <input type="text" name="empresa" id="empresa" value="{{ old('empresa') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                           placeholder="Ej: Mi Negocio S.L.">
                </div>
            </div>


            <!-- Botón de Envío -->
            <div class="mt-8">
                <button type="submit"
                        class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-green-500 focus:ring-opacity-50 transition duration-300 transform hover:scale-[1.01]">
                    Guardar Cliente
                </button>
            </div>
        </form>
    </div>
</body>
</html>
