<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['index']);
    }

    public function index(Request $request)
    {
        if (auth()->user()->role === 'aluno') {
            abort(403);
        }
        $query = Curso::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                    ->orWhere('area', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        $cursos = $query->paginate(10);
        return view('admin.cursos.index', compact('cursos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'area' => 'required|string|max:255',
        ]);

        Curso::create($request->all());

        return redirect()->back()->with('success', 'Curso criado com sucesso!');
    }

    public function update(Request $request, Curso $curso)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'area' => 'required|string|max:255',
        ]);

        $curso->update($request->all());

        return redirect()->back()->with('success', 'Curso atualizado com sucesso!');
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();
        return redirect()->back()->with('success', 'Curso removido com sucesso!');
    }
}
