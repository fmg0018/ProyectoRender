<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @hasSection('title')
            @yield('title')
        @else
            CRM Sistema
        @endif
    </title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('crm-favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('crm-favicon.svg') }}">

    <style>
        .app-header-inner {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .app-header-shortcuts {
            display: inline-flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: flex-start;
        }

        .app-shortcut-card-link {
            text-decoration: none;
            color: inherit;
            display: inline-flex;
        }

        .app-shortcut-card {
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            padding: 0.9rem 1.35rem;
            border-radius: 1.1rem;
            background: #ffffff;
            border: 1px solid rgba(148, 163, 184, 0.25);
            box-shadow: 0 18px 32px rgba(15, 23, 42, 0.12);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            cursor: pointer;
            justify-content: space-between;
        }

        .app-shortcut-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 22px 40px rgba(15, 23, 42, 0.16);
        }

        .app-shortcut-card__icon {
            width: 3rem;
            height: 3rem;
            border-radius: 1.1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.35rem;
            box-shadow: 0 16px 30px rgba(59, 130, 246, 0.28);
        }

        .app-shortcut-card__label {
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
        }

        .app-shortcut-card__chevron {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 0.95rem;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .app-shortcut-card:hover .app-shortcut-card__chevron {
            transform: translateX(4px);
        }

        .app-shortcut-card-link:focus-visible .app-shortcut-card {
            outline: 3px solid rgba(37, 99, 235, 0.45);
            outline-offset: 3px;
        }

        .app-shortcut-card--clientes .app-shortcut-card__icon {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            box-shadow: 0 16px 30px rgba(59, 130, 246, 0.32);
        }

        .app-shortcut-card--clientes:hover {
            border-color: rgba(59, 130, 246, 0.45);
        }

        .app-shortcut-card--clientes:hover .app-shortcut-card__chevron {
            color: #2563eb;
        }

        .app-shortcut-card--facturas .app-shortcut-card__icon {
            background: linear-gradient(135deg, #22c55e, #15803d);
            box-shadow: 0 16px 30px rgba(34, 197, 94, 0.32);
        }

        .app-shortcut-card--facturas:hover {
            border-color: rgba(34, 197, 94, 0.45);
        }

        .app-shortcut-card--facturas:hover .app-shortcut-card__chevron {
            color: #16a34a;
        }

        .app-shortcut-card--incidencias .app-shortcut-card__icon {
            background: linear-gradient(135deg, #f97316, #c2410c);
            box-shadow: 0 16px 30px rgba(249, 115, 22, 0.34);
        }

        .app-shortcut-card--incidencias:hover {
            border-color: rgba(249, 115, 22, 0.45);
        }

        .app-shortcut-card--incidencias:hover .app-shortcut-card__chevron {
            color: #ea580c;
        }

        @media (max-width: 767px) {
            .app-header-shortcuts {
                justify-content: center;
            }

            .app-shortcut-card-link {
                width: 100%;
            }

            .app-shortcut-card {
                width: 100%;
            }

            .app-shortcut-card__label {
                font-size: 0.95rem;
            }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="font-sans antialiased bg-slate-100">
    @php
        $hideTopNav = filter_var(trim($__env->yieldContent('hide_top_nav', 'false')), FILTER_VALIDATE_BOOLEAN);
    @endphp
    <div class="min-h-screen bg-slate-100">
        @unless ($hideTopNav)
        <header class="bg-white shadow-sm border-bottom border-light-subtle">
            <div class="container py-4">
                <div class="app-header-inner d-flex flex-column flex-lg-row align-items-lg-center justify-content-center gap-4">
                    <a href="{{ route('dashboard') }}" class="text-decoration-none flex-grow-0">
                        <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-3 shadow" style="width: 3.25rem; height: 3.25rem; background: linear-gradient(135deg, #2563eb, #1d4ed8);">
                                    <i class="fas fa-chart-line text-white fs-5"></i>
                                </div>
                                <div>
                                    <h1 class="mb-0 fw-bold text-dark">CRM Sistema</h1>
                                    <p class="mb-0 text-secondary small">Panel de control del sistema</p>
                                </div>
                        </div>
                    </a>

                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-3 flex-grow-0">
                        <div class="app-header-shortcuts">
                            @if (Route::has('clientes.index'))
                                <a href="{{ route('clientes.index') }}" class="app-shortcut-card-link" aria-label="{{ __('Gestionar Clientes') }}">
                                    <span class="app-shortcut-card app-shortcut-card--clientes">
                                        <span class="app-shortcut-card__icon">
                                            <i class="fas fa-users"></i>
                                        </span>
                                        <span class="app-shortcut-card__label">{{ __('Gestionar Clientes') }}</span>
                                        <span class="app-shortcut-card__chevron">
                                            <i class="fas fa-arrow-right"></i>
                                        </span>
                                    </span>
                                </a>
                            @endif
                            @if (Route::has('facturas.index'))
                                <a href="{{ route('facturas.index') }}" class="app-shortcut-card-link" aria-label="{{ __('Gestionar Facturas') }}">
                                    <span class="app-shortcut-card app-shortcut-card--facturas">
                                        <span class="app-shortcut-card__icon">
                                            <i class="fas fa-file-invoice"></i>
                                        </span>
                                        <span class="app-shortcut-card__label">{{ __('Gestionar Facturas') }}</span>
                                        <span class="app-shortcut-card__chevron">
                                            <i class="fas fa-arrow-right"></i>
                                        </span>
                                    </span>
                                </a>
                            @endif
                            @if (Route::has('incidencias.index'))
                                <a href="{{ route('incidencias.index') }}" class="app-shortcut-card-link" aria-label="{{ __('Gestionar Incidencias') }}">
                                    <span class="app-shortcut-card app-shortcut-card--incidencias">
                                        <span class="app-shortcut-card__icon">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                        <span class="app-shortcut-card__label">{{ __('Gestionar Incidencias') }}</span>
                                        <span class="app-shortcut-card__chevron">
                                            <i class="fas fa-arrow-right"></i>
                                        </span>
                                    </span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </header>
        @endunless

        @isset($header)
            <header class="bg-white shadow">
                <div class="container py-4">
                    {{ $header }}
                </div>
            </header>
        @endisset

    <main class="pt-0 pb-4">
            <div class="container">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('status'))
                    <div class="alert alert-info">{{ session('status') }}</div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
