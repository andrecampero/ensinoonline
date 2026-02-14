<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Top 5 Cursos com mais matrículas
        $topCursos = \App\Models\Curso::withCount('matriculas')
            ->orderBy('matriculas_count', 'desc')
            ->take(5)
            ->get();

        // Top 5 Alunos com mais matrículas
        $topAlunos = \App\Models\User::whereHas('perfil', function ($q) {
            $q->where('nome_perfil', 'Aluno');
        })
            ->withCount('matriculas')
            ->orderBy('matriculas_count', 'desc')
            ->take(5)
            ->get();

        $totalCursos = \App\Models\Curso::count();
        $totalDisciplinas = \App\Models\Disciplina::count();

        $totalAlunos = \App\Models\User::whereHas('perfil', function ($q) {
            $q->where('nome_perfil', 'Aluno');
        })->count();

        $totalProfessores = \App\Models\User::whereHas('perfil', function ($q) {
            $q->where('nome_perfil', 'Professor');
        })->count();

        return view('home', compact('topCursos', 'topAlunos', 'totalCursos', 'totalDisciplinas', 'totalAlunos', 'totalProfessores'));
    }

    public function root()
    {
        return redirect('/login');
    }

    public function homeRedirect()
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isProfessor()) {
            return redirect()->route('professores.index');
        }
        return redirect()->route('alunos.index');
    }
}
