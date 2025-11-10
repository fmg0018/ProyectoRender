<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'CRM'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-gray-100 font-sans antialiased">
    <div class="flex min-h-screen flex-col">
        <header class="bg-white shadow-sm">
            <nav class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-6">
                    <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-gray-900">
                        {{ config('app.name', 'CRM') }}
                    </a>

                    <ul class="hidden gap-4 text-sm font-medium text-gray-600 sm:flex">
                        <li>
                            <a href="{{ route('dashboard') }}" class="transition hover:text-gray-900">
                                {{ __('Inicio') }}
                            </a>
                        </li>
                        @if (Route::has('clientes.index'))
                            <li>
                                <a href="{{ route('clientes.index') }}" class="transition hover:text-gray-900">
                                    {{ __('Clientes') }}
                                </a>
                            </li>
                        @endif
                        @if (Route::has('facturas.index'))
                            <li>
                                <a href="{{ route('facturas.index') }}" class="transition hover:text-gray-900">
                                    {{ __('Facturas') }}
                                </a>
                            </li>
                        @endif
                        @if (Route::has('incidencias.index'))
                            <li>
                                <a href="{{ route('incidencias.index') }}" class="transition hover:text-gray-900">
                                    {{ __('Incidencias') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="hidden items-center gap-4 sm:flex">
                    @auth
                        <div class="flex items-center gap-3">
                            <!-- User Avatar -->
                            <div class="h-8 w-8 rounded-full bg-orange-500 flex items-center justify-center">
                                <span class="text-white font-semibold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            </div>
                            <!-- User Name -->
                            <span class="text-sm font-medium text-gray-700">
                                {{ auth()->user()->name }}
                            </span>
                            <!-- Logout Button -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __('Cerrar Sesión') }}
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="rounded-md border border-orange-500 bg-orange-500 px-4 py-2 text-sm font-semibold uppercase tracking-widest text-white shadow-sm transition hover:scale-[1.05]">
                            {{ __('Acceder') }}
                        </a>
                    @endauth
                </div>
            </nav>
        </header>

        <main class="flex-1">
            <div class="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                @if (session('status'))
                    <div class="mb-6 rounded-md border border-emerald-300 bg-emerald-50 p-4 text-sm text-emerald-900">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <footer class="bg-white">
            <div class="mx-auto w-full max-w-7xl px-4 py-6 text-center text-xs uppercase tracking-widest text-gray-500 sm:px-6 lg:px-8">
                &copy; {{ now()->year }} {{ config('app.name', 'CRM') }}. {{ __('Todos los derechos reservados.') }}
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>