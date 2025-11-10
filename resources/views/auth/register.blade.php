@extends('layouts.app')

@section('hide_top_nav', 'true')

@section('title', 'Crear cuenta')

@push('head')
<style>
    .register-hero {
        min-height: 100vh;
        background: linear-gradient(135deg, #ffffff 0%, #fff7ed 100%);
        color: #111827;
    }

    .register-card {
        border-radius: 1.5rem;
        overflow: hidden;
        border: 1px solid rgba(249, 115, 22, 0.1);
        box-shadow: 0 25px 65px rgba(249, 115, 22, 0.15);
        background: #ffffff;
    }

    .register-card .card-header {
        background: linear-gradient(135deg, #f97316, #ea580c);
        color: #fff3e8;
    }

    .register-input-group .input-group-text {
        background: #fff7ed;
        border-right: none;
        color: #f97316;
        border-color: rgba(249, 115, 22, 0.35);
    }

    .register-input-group .form-control {
        border-left: none;
        border-color: rgba(249, 115, 22, 0.35);
        font-size: 1.05rem;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }

    .register-input-group .form-control:focus {
        box-shadow: none;
        border-color: #fb923c;
    }

    .register-meta {
        font-size: 0.95rem;
        color: #4b5563;
    }

    .register-meta i {
        color: #f97316;
    }

    .btn-register {
        background: linear-gradient(135deg, #f97316, #ea580c);
        border: none;
        color: #ffffff;
        box-shadow: 0 18px 35px rgba(249, 115, 22, 0.35);
    }

    .btn-register:hover {
        background: linear-gradient(135deg, #fb923c, #f97316);
        color: #ffffff;
    }
</style>
@endpush

@section('content')
<div class="register-hero d-flex align-items-center py-5">
    <div class="container">
        <div class="row align-items-center justify-content-center g-5">
            <div class="col-xl-6 text-center text-xl-start">
                <h1 class="display-5 fw-bold mb-4 text-dark">Crear una cuenta</h1>
                <p class="lead text-secondary mb-5">Únete al CRM para gestionar clientes, facturas e incidencias con una experiencia optimizada y colaborativa.</p>
                <div class="d-flex flex-column flex-sm-row gap-3 register-meta">
                    <span class="d-inline-flex align-items-center gap-2"><i class="bi bi-person-plus-fill"></i> Colabora con tu equipo</span>
                    <span class="d-inline-flex align-items-center gap-2"><i class="bi bi-shield-lock-fill"></i> Seguridad avanzada</span>
                    <span class="d-inline-flex align-items-center gap-2"><i class="bi bi-bar-chart-line-fill"></i> Seguimiento en tiempo real</span>
                </div>
            </div>

            <div class="col-lg-6 col-xl-5">
                <div class="card register-card">
                    <div class="card-header py-4 px-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="badge bg-warning text-dark mb-2">CRM Sistema</span>
                                <h2 class="h4 fw-semibold mb-0">Crear cuenta</h2>
                            </div>
                            <i class="fas fa-user-plus fa-lg text-warning"></i>
                        </div>
                    </div>
                    <div class="card-body p-4 p-xl-5">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4" role="alert">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="bi bi-exclamation-octagon-fill fs-5"></i>
                                    <div>
                                        <strong>Revisa los datos ingresados</strong>
                                        <ul class="mb-0 mt-2 ps-4 small">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" novalidate>
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">Nombre completo</label>
                                <div class="input-group input-group-lg register-input-group">
                                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}"
                                        placeholder="Ej. Ana Pérez"
                                        required
                                        autofocus
                                    >
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                                <div class="input-group input-group-lg register-input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}"
                                        placeholder="usuario@empresa.com"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Contraseña</label>
                                <div class="input-group input-group-lg register-input-group">
                                    <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="••••••••"
                                        required
                                        autocomplete="new-password"
                                    >
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirmar contraseña</label>
                                <div class="input-group input-group-lg register-input-group">
                                    <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        placeholder="Repite la contraseña"
                                        required
                                        autocomplete="new-password"
                                    >
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="1" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Acepto los <a href="#" class="text-warning fw-semibold">términos y condiciones</a> del servicio.
                                </label>
                            </div>

                            <button type="submit" class="btn btn-register btn-lg w-100 shadow-sm">
                                Crear cuenta
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <span class="text-muted">¿Ya tienes una cuenta?</span>
                            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold ms-1 text-warning">Inicia sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
