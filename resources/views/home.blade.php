@extends('layouts.app')

@section('content')
    <div class="row align-items-center mb-4">
        <div class="col-auto">
            <span class="badge bg-white text-primary rounded-pill px-3 py-2 shadow-sm border">
                <i class="fas fa-shield-alt me-1"></i> Perfil: {{ ucfirst(auth()->user()->role) }}
            </span>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary-soft p-3 me-3" style="background-color: rgba(90, 90, 90, 0.1);">
                        <i class="fas fa-book-open text-primary fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small">Total Cursos</h6>
                        <h4 class="fw-bold mb-0">{{ $totalCursos }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-3 me-3" style="background-color: rgba(0, 182, 122, 0.1);">
                        <i class="fas fa-user-graduate text-success fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small">Alunos Ativos</h6>
                        <h4 class="fw-bold mb-0">{{ $totalAlunos }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-3 me-3" style="background-color: rgba(255, 185, 0, 0.1);">
                        <i class="fas fa-user-tie text-warning fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small">Professores</h6>
                        <h4 class="fw-bold mb-0">{{ $totalProfessores }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-3 me-3" style="background-color: rgba(238, 93, 114, 0.1);">
                        <i class="fas fa-tasks text-danger fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small">Disciplinas</h6>
                        <h4 class="fw-bold mb-0">{{ $totalDisciplinas }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <dashboard-charts :top-cursos='@json($topCursos)' :top-alunos='@json($topAlunos)'>
    </dashboard-charts>
@endsection