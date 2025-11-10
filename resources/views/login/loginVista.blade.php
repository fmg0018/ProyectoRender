{{-- Vista de Login --}}
@extends('layouts.app')

@section('hide_top_nav', 'true')

@section('title', 'Iniciar Sesión')

@push('head')
<style>
    .login-hero {
        min-height: 100vh;
        background: linear-gradient(135deg, #ffffff 0%, #fff7ed 100%);
        color: #111827;
    }

    .login-card {
        border-radius: 1.5rem;
        overflow: hidden;
        border: 1px solid rgba(249, 115, 22, 0.1);
        box-shadow: 0 25px 65px rgba(249, 115, 22, 0.15);
        background: #ffffff;
    }

    .login-card .card-header {
        background: linear-gradient(135deg, #f97316, #ea580c);
        color: #fff3e8;
    }

    .login-input-group .input-group-text {
        background: #fff7ed;
        border-right: none;
        color: #f97316;
        border-color: rgba(249, 115, 22, 0.35);
    }

    .login-input-group .form-control {
        border-left: none;
        border-color: rgba(249, 115, 22, 0.35);
        font-size: 1.05rem;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }

    .login-input-group .form-control:focus {
        box-shadow: none;
        border-color: #fb923c;
    }

    .login-meta {
        font-size: 0.95rem;
        color: #4b5563;
    }

    .login-meta i {
        color: #f97316;
    }

    .btn-login {
        background: linear-gradient(135deg, #f97316, #ea580c);
        border: none;
        color: #ffffff;
        box-shadow: 0 18px 35px rgba(249, 115, 22, 0.35);
    }

    .btn-login:hover {
        background: linear-gradient(135deg, #fb923c, #f97316);
        color: #ffffff;
    }
</style>
@endpush

@section('content')
<div class="login-hero d-flex align-items-center py-5">
    <div class="container">
        <div class="row align-items-center justify-content-center g-5">
            <div class="col-xl-6 text-center text-xl-start">
                <h1 class="display-5 fw-bold mb-4 text-dark">Bienvenido de vuelta</h1>
                <p class="lead text-secondary mb-5">Accede al panel CRM para gestionar clientes, facturas e incidencias con una experiencia optimizada y en tiempo real.</p>
                <div class="d-flex flex-column flex-sm-row gap-3 login-meta">
                    <span class="d-inline-flex align-items-center gap-2"><i class="bi bi-shield-lock-fill"></i> Autenticación segura</span>
                    <span class="d-inline-flex align-items-center gap-2"><i class="bi bi-lightning-charge-fill"></i> Acceso inmediato</span>
                    <span class="d-inline-flex align-items-center gap-2"><i class="bi bi-graph-up-arrow"></i> Métricas en tiempo real</span>
                </div>
            </div>

            <div class="col-lg-6 col-xl-5">
                <div class="card login-card">
                    <div class="card-header py-4 px-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="badge bg-warning text-dark mb-2">CRM Sistema</span>
                                <h2 class="h4 fw-semibold mb-0">Iniciar sesión</h2>
                            </div>
                            <i class="fas fa-lock fa-lg text-warning"></i>
                        </div>
                    </div>
                    <div class="card-body p-4 p-xl-5">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4" role="alert">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="bi bi-exclamation-octagon-fill fs-5"></i>
                                    <div>
                                        <strong>Revisa tus credenciales</strong>
                                        <ul class="mb-0 mt-2 ps-4 small">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" novalidate>
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                                <div class="input-group input-group-lg login-input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}"
                                        placeholder="usuario@empresa.com"
                                        required
                                        autofocus
                                    >
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Contraseña</label>
                                <div class="input-group input-group-lg login-input-group">
                                    <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="••••••••"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        Mantener sesión iniciada
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-decoration-none small text-warning fw-semibold">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-login btn-lg w-100 shadow-sm">
                                Acceder al panel
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <span class="text-muted">¿Aún no tienes cuenta?</span>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-decoration-none fw-semibold ms-1 text-warning">Crear una cuenta</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection