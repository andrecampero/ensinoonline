<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (auth()->user()->isAluno()) {
            abort(403);
        }

        $query = Matricula::with(['usuario', 'curso']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('usuario', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('curso', function ($q) use ($search) {
                    $q->where('titulo', 'like', "%{$search}%");
                });
            });
        }

        $matriculas = $query->paginate(10);

        // Fetch all students and courses for the creation modal dropdowns
        $alunos = \App\Models\User::whereHas('perfil', function ($q) {
            $q->where('nome_perfil', 'Aluno');
        })->orderBy('name')->get();

        $cursos = \App\Models\Curso::orderBy('titulo')->get();

        return view('admin.matriculas.index', compact('matriculas', 'alunos', 'cursos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuarios_id' => 'required|exists:usuarios,id',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        // Prevent duplicate enrollment
        $exists = \App\Models\Matricula::where('usuarios_id', $request->usuarios_id)
            ->where('curso_id', $request->curso_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'O aluno já está matriculado neste curso!');
        }

        \App\Models\Matricula::create($request->all());

        return redirect()->back()->with('success', 'Matrícula realizada com sucesso!');
    }

    public function update(Request $request, \App\Models\Matricula $matricula)
    {
        $request->validate([
            'usuarios_id' => 'required|exists:usuarios,id',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $matricula->update($request->all());

        return redirect()->back()->with('success', 'Matrícula atualizada com sucesso!');
    }

    public function destroy(\App\Models\Matricula $matricula)
    {
        $matricula->delete();
        return redirect()->back()->with('success', 'Matrícula removida com sucesso!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

}
