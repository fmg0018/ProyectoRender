<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido | CRM Sistema</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('crm-favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-lg border-b border-gray-200 fixed w-full top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-5">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-lg p-2 mr-3">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <span class="text-gray-900 text-xl font-bold tracking-wide">CRM Sistema</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-gray-900 transition-colors font-medium px-3 py-2 rounded-md hover:bg-gray-100">Características</a>
                    <a href="#about" class="text-gray-700 hover:text-gray-900 transition-colors font-medium px-3 py-2 rounded-md hover:bg-gray-100">Acerca de</a>
                    <a href="{{ route('login') }}" class="bg-orange-500 text-gray-900 px-6 py-2.5 rounded-lg font-semibold hover:bg-orange-400 transition-colors shadow-lg border border-orange-400">Iniciar Sesión</a>
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button class="text-gray-700 p-2 rounded-md hover:bg-gray-100">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-24 px-4">
        <div class="max-w-6xl mx-auto text-center">
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900 mb-8 leading-tight">
                Gestiona tu negocio
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-400 mt-2">con inteligencia</span>
            </h1>
            <p class="text-lg sm:text-xl md:text-2xl text-gray-800 mb-12 max-w-4xl mx-auto leading-relaxed px-4">
                Sistema CRM completo con integración N8N para automatizar tus procesos de ventas, 
                gestión de clientes y seguimiento de incidencias de manera profesional.
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center max-w-lg mx-auto">
                <a href="{{ route('login') }}" class="w-full sm:w-auto bg-gradient-to-r from-orange-400 to-red-500 text-gray-900 px-8 py-4 rounded-xl font-bold text-lg hover:from-orange-300 hover:to-red-400 transition-all shadow-xl transform hover:scale-105 border border-orange-300">
                    <i class="fas fa-sign-in-alt mr-3"></i>
                    Iniciar Sesión
                </a>
                <a href="{{ route('register') }}" class="w-full sm:w-auto bg-white border-2 border-gray-300 text-gray-800 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-50 hover:border-gray-400 transition-all shadow-lg">
                    <i class="fas fa-user-plus mr-3"></i>
                    Registrarse
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-gradient-to-br from-slate-50 to-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-6">Características principales</h2>
                <p class="text-lg sm:text-xl text-gray-700 max-w-3xl mx-auto leading-relaxed">Todo lo que necesitas para gestionar tu negocio de manera eficiente y profesional</p>
            </div>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
                <div class="group text-center p-8 bg-white rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-slate-200">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fas fa-tachometer-alt text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Dashboard</h3>
                    <p class="text-gray-700 leading-relaxed">Panel de control con métricas y estadísticas en tiempo real para tu negocio</p>
                </div>

                <div class="group text-center p-8 bg-white rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-slate-200">
                    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Clientes</h3>
                    <p class="text-gray-700 leading-relaxed">Gestión completa y organizada de tu base de datos de clientes</p>
                </div>

                <div class="group text-center p-8 bg-white rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-slate-200">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-invoice text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Facturas</h3>
                    <p class="text-gray-700 leading-relaxed">Sistema de facturación automatizado y profesional</p>
                </div>

                <div class="group text-center p-8 bg-white rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-slate-200">
                    <div class="bg-gradient-to-br from-red-500 to-red-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fas fa-exclamation-triangle text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Incidencias</h3>
                    <p class="text-gray-700 leading-relaxed">Seguimiento y resolución eficaz de problemas y tickets</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-8">
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 leading-tight">
                        Potencia tu negocio con 
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">N8N</span>
                    </h2>
                    <p class="text-lg sm:text-xl text-gray-700 leading-relaxed">
                        Nuestro sistema CRM integra la potencia de N8N para automatizar workflows, 
                        sincronizar datos y optimizar tus procesos de negocio de manera inteligente.
                    </p>
                    <ul class="space-y-5">
                        <li class="flex items-start">
                            <div class="bg-green-100 rounded-full p-3 mr-4 mt-1 flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <span class="text-lg text-gray-800">Automatización de tareas repetitivas</span>
                        </li>
                        <li class="flex items-start">
                            <div class="bg-green-100 rounded-full p-3 mr-4 mt-1 flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <span class="text-lg text-gray-800">Integración con servicios externos</span>
                        </li>
                        <li class="flex items-start">
                            <div class="bg-green-100 rounded-full p-3 mr-4 mt-1 flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <span class="text-lg text-gray-800">Notificaciones inteligentes</span>
                        </li>
                        <li class="flex items-start">
                            <div class="bg-green-100 rounded-full p-3 mr-4 mt-1 flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <span class="text-lg text-gray-800">Reportes automáticos</span>
                        </li>
                    </ul>
                </div>
                <div class="flex justify-center lg:justify-end">
                    <div class="bg-gradient-to-br from-orange-50 to-red-100 rounded-3xl p-10 shadow-2xl border border-orange-200 max-w-md w-full">
                        <div class="text-center space-y-6">
                            <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl p-8 inline-block shadow-xl">
                                <i class="fas fa-robot text-white text-5xl"></i>
                            </div>
                            <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">Automatización Inteligente</h3>
                            <p class="text-gray-700 text-lg leading-relaxed">Deja que la tecnología trabaje por ti mientras te enfocas en hacer crecer tu negocio</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center mb-4">
                    <i class="fas fa-chart-line text-2xl mr-3"></i>
                    <span class="text-xl font-bold">CRM Sistema</span>
                </div>
                <p class="text-gray-400 mb-4">Sistema de gestión empresarial con integración N8N</p>
                <p class="text-gray-500 text-sm"> 2025 ZaitecS25 Internship Project. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
