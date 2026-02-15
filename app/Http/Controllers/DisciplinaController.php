<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use Illuminate\Http\Request;

class DisciplinaController extends Controller
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
        $query = Disciplina::with(['curso', 'usuario']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%")
                    ->orWhereHas('curso', function ($q) use ($search) {
                        $q->where('titulo', 'like', "%{$search}%");
                    })
                    ->orWhereHas('usuario', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $disciplinas = $query->paginate(10);
        $cursos = \App\Models\Curso::all();
        // Fetch professors from Users table
        $professores = \App\Models\User::whereHas('perfil', function ($q) {
            $q->where('nome_perfil', 'Professor');
        })->orderBy('name')->get();

        return view('admin.disciplinas.index', compact('disciplinas', 'cursos', 'professores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'curso_id' => 'required|exists:cursos,id',
            'usuarios_id' => 'required|exists:usuarios,id',
        ]);

        \App\Models\Disciplina::create($request->all());

        return redirect()->back()->with('success', 'Disciplina cadastrada com sucesso!');
    }

    public function update(Request $request, \App\Models\Disciplina $disciplina)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'curso_id' => 'required|exists:cursos,id',
            'usuarios_id' => 'required|exists:usuarios,id',
        ]);

        $disciplina->update($request->all());

        return redirect()->back()->with('success', 'Disciplina atualizada com sucesso!');
    }

    public function destroy(\App\Models\Disciplina $disciplina)
    {
        $disciplina->delete();
        return redirect()->back()->with('success', 'Disciplina removida com sucesso!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

}
