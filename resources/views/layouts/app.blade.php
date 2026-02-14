<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f4f7fe;
            color: #2b3674;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(224, 225, 226, 0.5);
        }

        .navbar-brand {
            font-weight: 700;
            color: #5a5a5a !important;
            letter-spacing: -0.5px;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background-color: #5a5a5a;
            border-color: #5a5a5a;
            border-radius: 12px;
            padding: 10px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #404040;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(90, 90, 90, 0.3);
            border-color: #404040;
        }

        .btn-primary:active,
        .btn-primary:focus,
        .btn-primary:focus-visible {
            background-color: #333333 !important;
            border-color: #333333 !important;
            box-shadow: 0 0 0 0.25rem rgba(90, 90, 90, 0.5) !important;
        }

        .nav-link {
            font-weight: 500;
            color: #a3aed0 !important;
            transition: color 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #5a5a5a !important;
        }

        .text-primary {
            color: #5a5a5a !important;
        }

        .bg-primary-soft {
            background-color: rgba(90, 90, 90, 0.1) !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #5a5a5a;
            box-shadow: 0 0 0 0.25rem rgba(90, 90, 90, 0.25);
        }

        .dropdown-menu {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-graduation-cap me-2"></i> {{ config('app.name', 'Ensino Online') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'professor')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/professores*') ? 'active' : '' }}"
                                        href="{{ route('professores.index') }}">Professores</a>
                                </li>
                            @endif

                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'aluno' || auth()->user()->role === 'professor')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/alunos*') ? 'active' : '' }}"
                                        href="{{ route('alunos.index') }}">Alunos</a>
                                </li>
                            @endif

                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'professor')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/cursos*') ? 'active' : '' }}"
                                        href="{{ route('cursos.index') }}">Cursos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/disciplinas*') ? 'active' : '' }}"
                                        href="{{ route('disciplinas.index') }}">Disciplinas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/matriculas*') ? 'active' : '' }}"
                                        href="{{ route('matriculas.index') }}">Matrículas</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Entrar</a>
                                </li>
                            @endif
                        @else
                            @if(auth()->user()->isProfessor() || auth()->user()->isAdmin())
                                <li class="nav-item d-flex align-items-center me-3">
                                    <a href="{{ route('relatorios.faixa_etaria') }}"
                                        class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                        title="Relatório de Faixa Etária">
                                        <i class="fas fa-chart-pie me-2"></i> Relatório
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-dark" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-edit me-2"></i> Atualizar Dados Cadastrais
                                    </a>
                                    <hr class="dropdown-divider">

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                                                                         document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Sair
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-5">
            <div class="container">
                @if(session('error'))
                    <div class="alert alert-danger border-0 rounded-4 mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success border-0 rounded-4 mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    @yield('scripts')
</body>

</html>