<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | CRM Sistema</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" type="image/svg+xml" href="{{ asset('crm-favicon.svg') }}">
</head>
<body class="bg-gray-50 min-h-screen">
    
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="bg-blue-600 rounded-lg p-3 mr-4">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Dashboard CRM</h1>
                        <p class="text-gray-600 mt-1">Panel de control del sistema</p>
                    </div>
                </div>
                    <div class="flex items-center bg-blue-50 rounded-lg px-4 py-2 space-x-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="text-sm">
                                <p class="text-blue-900 font-semibold leading-tight">{{ auth()->user()->name ?? 'Usuario' }}</p>
                                <p class="text-blue-700 text-xs">{{ auth()->user()->email ?? '' }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="px-4 sm:px-0">
            
            <!-- Tarjetas de estadísticas -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                
                <!-- Total Clientes -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background-color: #3B82F6 !important;">
                                    <i class="fas fa-users text-white text-2xl" style="color: white !important;"></i>
                                </div>
                            </div>
                            <div class="ml-4 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Clientes</dt>
                                    <dd class="text-3xl font-bold text-gray-900 mt-1">{{ $totalClientes }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-blue-50 px-6 py-3 border-t border-blue-100">
                        <div class="text-sm">
                            <span class="text-blue-700 font-semibold">{{ $clientesActivos }} activos</span>
                            <span class="text-gray-500 ml-2">de {{ $totalClientes }}</span>
                        </div>
                    </div>
                </div>

                <!-- Total Facturas -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-file-invoice text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Facturas</dt>
                                    <dd class="text-3xl font-bold text-gray-900 mt-1">{{ $totalFacturas }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 px-6 py-3 border-t border-green-100">
                        <div class="text-sm">
                            <span class="text-green-700 font-semibold">{{ $facturasPagadas }} pagadas</span>
                            <span class="text-yellow-600 font-semibold ml-2">{{ $facturasPendientesSinVencer }} pendientes</span>
                            @if($facturasVencidas > 0)
                                <span class="text-red-600 font-semibold ml-2">{{ $facturasVencidas }} vencidas</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Ingresos Totales -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-dollar-sign text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 uppercase tracking-wide">Ingresos Totales</dt>
                                    <dd class="text-3xl font-bold text-gray-900 mt-1">${{ number_format($ingresosTotales, 2) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 px-6 py-3 border-t border-green-100">
                        <div class="text-sm">
                            <span class="text-green-700 font-semibold">Facturado</span>
                            @if($facturasPendientes > 0)
                                <span class="text-gray-500 ml-2">{{ $facturasPendientes }} por cobrar</span>
                                @if($facturasVencidas > 0)
                                    <span class="text-red-600 font-semibold ml-1">({{ $facturasVencidas }} vencidas)</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Total Incidencias -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-orange-500 rounded-2xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Incidencias</dt>
                                    <dd class="text-3xl font-bold text-gray-900 mt-1">{{ $totalIncidencias }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-orange-50 px-6 py-3 border-t border-orange-100">
                        <div class="text-sm">
                            <span class="text-orange-700 font-semibold">{{ $incidenciasAbiertas }} abiertas</span>
                            <span class="text-gray-500 ml-2">{{ $incidenciasResueltas }} resueltas</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                
                <!-- Gráfico de Estados de Facturas -->
                <div class="bg-white shadow-lg rounded-xl border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                                <i class="fas fa-chart-pie text-green-700 text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Estados de Facturas</h3>
                                <p class="text-sm text-gray-500">Distribución por estado de pago</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="h-64">
                            <canvas id="facturasChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de Estados de Incidencias -->
                <div class="bg-white shadow-lg rounded-xl border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-gradient-to-br from-orange-100 to-orange-200 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                                <i class="fas fa-chart-bar text-orange-700 text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Estados de Incidencias</h3>
                                <p class="text-sm text-gray-500">Análisis por estado de resolución</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="h-64">
                            <canvas id="incidenciasChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enlaces rápidos -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                
                <!-- Gestionar Clientes -->
                <a href="{{ route('clientes.index') }}" class="group bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 hover:shadow-xl hover:border-blue-200 transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-xl group-hover:shadow-2xl group-hover:scale-105 transition-all duration-300">
                                    <i class="fas fa-users text-white text-3xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 uppercase tracking-wide">Gestión</dt>
                                    <dd class="text-xl font-bold text-gray-900 mt-1 group-hover:text-blue-700 transition-colors duration-300">Clientes</dd>
                                </dl>
                            </div>
                            <div class="ml-4">
                                <i class="fas fa-arrow-right text-gray-400 group-hover:text-blue-500 group-hover:translate-x-1 transition-all duration-300 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-blue-50 px-6 py-3 border-t border-blue-100">
                        <div class="text-sm">
                            <span class="text-blue-700 font-semibold">{{ $totalClientes }} clientes totales</span>
                            <span class="text-gray-500 ml-2">{{ $clientesActivos }} activos</span>
                        </div>
                    </div>
                </a>

                <!-- Gestionar Facturas -->
                <a href="{{ route('facturas.index') }}" class="group bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 hover:shadow-xl hover:border-green-200 transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-xl group-hover:shadow-2xl group-hover:scale-105 transition-all duration-300">
                                    <i class="fas fa-file-invoice text-white text-3xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 uppercase tracking-wide">Gestión</dt>
                                    <dd class="text-xl font-bold text-gray-900 mt-1 group-hover:text-green-700 transition-colors duration-300">Facturas</dd>
                                </dl>
                            </div>
                            <div class="ml-4">
                                <i class="fas fa-arrow-right text-gray-400 group-hover:text-green-500 group-hover:translate-x-1 transition-all duration-300 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 px-6 py-3 border-t border-green-100">
                        <div class="text-sm">
                            <span class="text-green-700 font-semibold">{{ $totalFacturas }} facturas totales</span>
                            @if($facturasVencidas > 0)
                                <span class="text-red-600 font-medium ml-2">{{ $facturasVencidas }} vencidas</span>
                            @else
                                <span class="text-gray-500 ml-2">{{ $facturasPendientes }} pendientes</span>
                            @endif
                        </div>
                    </div>
                </a>

                <!-- Gestionar Incidencias -->
                <a href="{{ route('incidencias.index') }}" class="group bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 hover:shadow-xl hover:border-orange-200 transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-xl group-hover:shadow-2xl group-hover:scale-105 transition-all duration-300">
                                    <i class="fas fa-exclamation-triangle text-white text-3xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 uppercase tracking-wide">Gestión</dt>
                                    <dd class="text-xl font-bold text-gray-900 mt-1 group-hover:text-orange-700 transition-colors duration-300">Incidencias</dd>
                                </dl>
                            </div>
                            <div class="ml-4">
                                <i class="fas fa-arrow-right text-gray-400 group-hover:text-orange-500 group-hover:translate-x-1 transition-all duration-300 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-orange-50 px-6 py-3 border-t border-orange-100">
                        <div class="text-sm">
                            <span class="text-orange-700 font-semibold">{{ $totalIncidencias }} incidencias totales</span>
                            <span class="text-gray-500 ml-2">{{ $incidenciasAbiertas }} abiertas</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </main>

    <!-- Scripts para gráficos -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de Estados de Facturas (Donut)
            const facturasCtx = document.getElementById('facturasChart').getContext('2d');
            new Chart(facturasCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pagadas', 'Pendientes', 'Vencidas'],
                    datasets: [{
                        data: [{{ $facturasPagadas }}, {{ $facturasPendientesSinVencer }}, {{ $facturasVencidas }}],
                        backgroundColor: [
                            '#10B981',  // Verde para pagadas
                            '#F59E0B',  // Amarillo para pendientes
                            '#EF4444'   // Rojo para vencidas
                        ],
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverBorderWidth: 4,
                        hoverBackgroundColor: [
                            '#059669',  // Verde más oscuro para hover
                            '#D97706',  // Amarillo más oscuro para hover
                            '#DC2626'   // Rojo más oscuro para hover
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 14,
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            cornerRadius: 8,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = {{ $totalFacturas }};
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    
                                    let description = '';
                                    if (label === 'Pagadas') {
                                        description = ' - Cobradas exitosamente';
                                    } else if (label === 'Pendientes') {
                                        description = ' - Dentro del plazo';
                                    } else if (label === 'Vencidas') {
                                        description = ' - Requieren atención urgente';
                                    }
                                    
                                    return `${label}: ${value} facturas (${percentage}%)${description}`;
                                }
                            }
                        }
                    },
                    cutout: '65%',
                    animation: {
                        animateRotate: true,
                        duration: 1000
                    }
                }
            });

            // Gráfico de Estados de Incidencias (Bar)
            const incidenciasCtx = document.getElementById('incidenciasChart').getContext('2d');
            new Chart(incidenciasCtx, {
                type: 'bar',
                data: {
                    labels: ['Abiertas', 'Resueltas'],
                    datasets: [{
                        label: 'Cantidad',
                        data: [{{ $incidenciasAbiertas }}, {{ $incidenciasResueltas }}],
                        backgroundColor: [
                            '#F97316',
                            '#10B981'
                        ],
                        borderRadius: 8,
                        borderSkipped: false,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            cornerRadius: 8,
                            padding: 12
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    weight: '600'
                                },
                                color: '#64748B'
                            },
                            grid: {
                                color: '#E2E8F0',
                                drawBorder: false
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    weight: '600'
                                },
                                color: '#64748B'
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        });
    </script>
</body>
</html>
