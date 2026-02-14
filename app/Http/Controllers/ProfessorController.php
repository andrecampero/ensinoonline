<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfessorController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['index']);
    }

    public function index(Request $request)
    {
        if (auth()->user()->isAluno()) {
            abort(403, 'Acesso não autorizado.'); // Alunos não podem ver professores
        }

        $query = User::whereHas('perfil', function ($q) {
            $q->where('nome_perfil', 'Professor');
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filtro_nome')) {
            $query->where('name', 'like', "%{$request->filtro_nome}%");
        }

        if ($request->filled('filtro_email')) {
            $query->where('email', 'like', "%{$request->filtro_email}%");
        }

        $professores = $query->paginate(10);
        return view('admin.professores.index', compact('professores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuarios,email',
            'data_nascimento' => 'nullable|date',
        ]);

        $perfilProfessor = Perfil::where('nome_perfil', 'Professor')->first();

        User::create([
            'name' => $request->nome,
            'email' => $request->email,
            'password' => Hash::make('Ensino@2026Online'),
            'perfil_id' => $perfilProfessor ? $perfilProfessor->id : 2,
            'data_nascimento' => $request->data_nascimento,
        ]);

        return redirect()->back()->with('success', 'Professor cadastrado com sucesso! Senha padrão: Ensino@2026Online');
    }

    public function update(Request $request, $id)
    {
        $professor = User::findOrFail($id);

        // Ensure user is a professor
        if (!$professor->isProfessor() && ($professor->perfil && $professor->perfil->nome_perfil !== 'Professor')) {
            // Fallback check if accessor fails or roles unmigrated
            abort(404, 'Professor não encontrado.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('usuarios')->ignore($professor->id),
            ],
            'data_nascimento' => 'nullable|date',
        ]);

        $professor->update([
            'name' => $request->nome,
            'email' => $request->email,
            'data_nascimento' => $request->data_nascimento,
        ]);

        return redirect()->back()->with('success', 'Professor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $professor = User::findOrFail($id);

        if (!$professor->isProfessor() && ($professor->perfil && $professor->perfil->nome_perfil !== 'Professor')) {
            abort(404, 'Professor não encontrado.');
        }

        $professor->delete();

        return redirect()->back()->with('success', 'Professor removido com sucesso!');
    }
}
