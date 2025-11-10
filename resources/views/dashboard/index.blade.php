@extends('layout.app')

@section('title', 'Dashboard - CRM')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600 mt-2">Bienvenido al sistema CRM - Resumen de actividades</p>
        </div>

        <!-- User Profile and Logout Button -->
        <div class="flex items-center space-x-6">
            <!-- User Profile -->
            <div class="flex items-center space-x-4">
                <div class="flex flex-col items-end">
                    <span class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</span>
                    <span class="text-sm text-gray-500">{{ auth()->user()->email }}</span>
                </div>
                <div class="h-10 w-10 rounded-full bg-orange-500 flex items-center justify-center">
                    <span class="text-white font-semibold text-lg">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                </div>
            </div>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Clientes -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Clientes</p>
                    <p class="text-3xl font-bold text-blue-600">1,247</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-500 text-sm font-medium">+12%</span>
                <span class="text-gray-600 text-sm">vs mes anterior</span>
            </div>
        </div>

        <!-- Total Facturas -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Facturas</p>
                    <p class="text-3xl font-bold text-green-600">856</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-500 text-sm font-medium">+8%</span>
                <span class="text-gray-600 text-sm">vs mes anterior</span>
            </div>
        </div>

        <!-- Incidencias Abiertas -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Incidencias Abiertas</p>
                    <p class="text-3xl font-bold text-orange-600">23</p>
                </div>
                <div class="p-3 bg-orange-100 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-red-500 text-sm font-medium">+3</span>
                <span class="text-gray-600 text-sm">desde ayer</span>
            </div>
        </div>

        <!-- Ingresos Mensuales -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Ingresos Mensuales</p>
                    <p class="text-3xl font-bold text-purple-600">€24,567</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-500 text-sm font-medium">+15%</span>
                <span class="text-gray-600 text-sm">vs mes anterior</span>
            </div>
        </div>
    </div>

    <!-- Gráficos y tablas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Gráfico de actividad -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actividad Semanal</h3>
            <div class="h-64 relative">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <!-- Distribución por estado -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Estado de Facturas</h3>
            <div class="flex items-center justify-between h-64">
                <div class="w-48 h-48 relative">
                    <canvas id="invoiceStatusChart"></canvas>
                </div>
                <div class="space-y-6 ml-6 flex-1">
                    <div class="flex items-center justify-between min-w-0">
                        <div class="flex items-center min-w-0 flex-1">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3 flex-shrink-0"></div>
                            <span class="text-gray-700 truncate">Pagadas</span>
                        </div>
                        <span class="font-semibold text-gray-900 ml-4 flex-shrink-0 text-right min-w-[80px]">642 (75%)</span>
                    </div>
                    <div class="flex items-center justify-between min-w-0">
                        <div class="flex items-center min-w-0 flex-1">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3 flex-shrink-0"></div>
                            <span class="text-gray-700 truncate">Pendientes</span>
                        </div>
                        <span class="font-semibold text-gray-900 ml-4 flex-shrink-0 text-right min-w-[80px]">157 (18%)</span>
                    </div>
                    <div class="flex items-center justify-between min-w-0">
                        <div class="flex items-center min-w-0 flex-1">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-3 flex-shrink-0"></div>
                            <span class="text-gray-700 truncate">Vencidas</span>
                        </div>
                        <span class="font-semibold text-gray-900 ml-4 flex-shrink-0 text-right min-w-[80px]">57 (7%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actividad reciente -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actividad Reciente</h3>
        <div class="space-y-4">
            <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                <div class="p-2 bg-blue-100 rounded-full mr-4">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Nuevo cliente registrado</p>
                    <p class="text-sm text-gray-600">Empresa ABC S.L. se ha registrado en el sistema</p>
                </div>
                <span class="text-xs text-gray-500">Hace 2 horas</span>
            </div>

            <div class="flex items-center p-3 bg-green-50 rounded-lg">
                <div class="p-2 bg-green-100 rounded-full mr-4">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Factura pagada</p>
                    <p class="text-sm text-gray-600">Factura #FAC-2024-0891 ha sido marcada como pagada</p>
                </div>
                <span class="text-xs text-gray-500">Hace 4 horas</span>
            </div>

            <div class="flex items-center p-3 bg-orange-50 rounded-lg">
                <div class="p-2 bg-orange-100 rounded-full mr-4">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Nueva incidencia</p>
                    <p class="text-sm text-gray-600">Problema técnico reportado por TechCorp Ltd.</p>
                </div>
                <span class="text-xs text-gray-500">Hace 6 horas</span>
            </div>
        </div>

        <!-- Botón ver todas -->
        <div class="mt-6 text-center">
            <button class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                Ver toda la actividad →
            </button>
        </div>
    </div>

    <!-- Accesos rápidos -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="/clientes" class="block bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <div>
                    <h4 class="text-lg font-semibold">Gestionar Clientes</h4>
                    <p class="text-blue-100 text-sm">Ver y administrar clientes</p>
                </div>
            </div>
        </a>

        <a href="/facturas" class="block bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-6 hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:scale-105">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <div>
                    <h4 class="text-lg font-semibold">Facturas</h4>
                    <p class="text-green-100 text-sm">Crear y gestionar facturas</p>
                </div>
            </div>
        </a>

        <a href="/incidencias" class="block bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg p-6 hover:from-orange-600 hover:to-orange-700 transition-all duration-200 transform hover:scale-105">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div>
                    <h4 class="text-lg font-semibold">Incidencias</h4>
                    <p class="text-orange-100 text-sm">Seguimiento de problemas</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection