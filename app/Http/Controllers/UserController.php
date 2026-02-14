<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::whereHas('perfil', function ($q) {
            $q->where('nome_perfil', 'Aluno');
        });

        // If logged user is student, only show their own record
        if (auth()->user()->isAluno()) {
            $query->where('id', auth()->id());
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $alunos = $query->withCount('matriculas')->paginate(10);
        return view('admin.alunos.index', compact('alunos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuarios',
            'data_nascimento' => 'nullable|date',
        ]);

        $perfilAluno = \App\Models\Perfil::where('nome_perfil', 'Aluno')->first();
        if (!$perfilAluno) {
            return redirect()->back()->with('error', 'Erro interno: Perfil de Aluno não encontrado.');
        }

        $data = $request->only(['name', 'email', 'data_nascimento']);
        $data['perfil_id'] = $perfilAluno->id;
        $data['password'] = Hash::make('Ensino@2026Online'); // Senha padrão

        User::create($data);

        return redirect()->back()->with('success', 'Aluno cadastrado com sucesso! A senha padrão é "Ensino@2026Online".');
    }

    public function update(Request $request, $id)
    {
        $aluno = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique('usuarios')->ignore($aluno->id),
            ],
            'data_nascimento' => 'nullable|date',
        ]);

        $aluno->update($request->only(['name', 'email', 'data_nascimento']));

        return redirect()->back()->with('success', 'Aluno atualizado com sucesso!');
    }

    public function destroy(User $aluno)
    {
        if (!$aluno->isAluno()) {
            return redirect()->back()->with('error', 'Ação não permitida para este tipo de usuário.');
        }

        $aluno->delete();
        return redirect()->back()->with('success', 'Aluno removido com sucesso!');
    }
}
