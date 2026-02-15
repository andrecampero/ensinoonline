<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Perfil;
use App\Models\Curso;
use App\Models\Disciplina;
use App\Models\Matricula;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Criar Perfis (IDs conforme print)
        $perfis = [
            ['id' => 1, 'nome_perfil' => 'Admin'],
            ['id' => 2, 'nome_perfil' => 'Professor'],
            ['id' => 3, 'nome_perfil' => 'Aluno'],
        ];

        foreach ($perfis as $data) {
            $perfil = Perfil::findOrNew($data['id']);
            $perfil->id = $data['id']; // Força o ID
            $perfil->fill($data);
            $perfil->save();
        }

        // Senha padrão para todos
        $password = Hash::make('Ensino@2026Online');
        $password_admin = Hash::make('Admin@2026Online');

        // 2. Criar Usuários (IDs e Dados conforme print)
        $usuarios = [
            // ID 1: Admin
            [
                'id' => 1,
                'name' => 'Administrador',
                'email' => 'admin@ensino.com',
                'password' => $password_admin,
                'perfil_id' => 1,
                'data_nascimento' => null,
            ],
            // ID 2: Emanuel Aluno
            [
                'id' => 2,
                'name' => 'Emanuel Aluno',
                'email' => 'emanuel_a@ensino.com',
                'password' => $password,
                'perfil_id' => 3,
                'data_nascimento' => '2000-01-01',
            ],
            // ID 3: Felipe Aluno
            [
                'id' => 3,
                'name' => 'Felipe Aluno',
                'email' => 'felipe@ensino.com',
                'password' => $password,
                'perfil_id' => 3,
                'data_nascimento' => '2001-01-09',
            ],
            // ID 5: Prof. Jubilut
            [
                'id' => 5,
                'name' => 'Prof. Jubilut',
                'email' => 'jubilut@ensino.com',
                'password' => $password,
                'perfil_id' => 2,
                'data_nascimento' => '1980-02-25',
            ],
            // ID 6: Prof. Allan
            [
                'id' => 6,
                'name' => 'Prof. Allan',
                'email' => 'allan@ensino.com.br',
                'password' => $password,
                'perfil_id' => 2,
                'data_nascimento' => '1990-02-20',
            ],
            // ID 7: Prof. João
            [
                'id' => 7,
                'name' => 'Prof. João',
                'email' => 'joaop@ensino.com',
                'password' => $password,
                'perfil_id' => 2,
                'data_nascimento' => '1980-02-10',
            ],
            // ID 8: Prof. Fabio
            [
                'id' => 8,
                'name' => 'Prof. Fabio',
                'email' => 'fabiop@ensino.com',
                'password' => $password,
                'perfil_id' => 2,
                'data_nascimento' => '1985-02-02',
            ],
        ];

        foreach ($usuarios as $data) {
            // Verifica pelo ID para garantir a integridade da chave primária
            $user = User::findOrNew($data['id']);
            $user->id = $data['id']; // Força o ID
            $user->fill($data);
            $user->save();
        }

        // 3. Criar Cursos
        $cursos = [
            [
                'id' => 1,
                'titulo' => 'Biologia Total',
                'area' => 'Ciências Biológicas',
                'descricao' => 'Curso completo de biologia para vestibulares.',
                'data_inicio' => '2026-02-14',
                'data_fim' => '2026-08-14',
            ],
            [
                'id' => 2,
                'titulo' => 'Matematica - Geometrias',
                'area' => 'Matemática',
                'descricao' => 'Estudo de Geometrias',
                'data_inicio' => '2026-02-01',
                'data_fim' => '2026-02-28',
            ]
        ];

        foreach ($cursos as $data) {
            $curso = Curso::findOrNew($data['id']);
            $curso->id = $data['id'];
            $curso->fill($data);
            $curso->save();
        }

        // 4. Criar Disciplinas
        $disciplinas = [
            [
                'id' => 1,
                'titulo' => 'Genética',
                'descricao' => 'Estudo dos genes e hereditariedade.',
                'curso_id' => 1, // Biologia
                'usuarios_id' => 5, // Jubilut
            ],
            [
                'id' => 2,
                'titulo' => 'Formas Geométricas',
                'descricao' => 'Estudo de formas geométricas',
                'curso_id' => 2, // Matematica
                'usuarios_id' => 6, // Allan
            ]
        ];

        foreach ($disciplinas as $data) {
            $disciplina = Disciplina::findOrNew($data['id']);
            $disciplina->id = $data['id'];
            $disciplina->fill($data);
            $disciplina->save();
        }

        // 5. Criar Matrículas
        $matriculas = [
            // User 2 (Emanuel) no Curso 1 (Biologia)
            ['id' => 1, 'usuarios_id' => 2, 'curso_id' => 1],
            // User 3 (Felipe) no Curso 2 (Matematica)
            ['id' => 2, 'usuarios_id' => 3, 'curso_id' => 2],
            // User 2 (Emanuel) no Curso 2 (Matematica)
            ['id' => 3, 'usuarios_id' => 2, 'curso_id' => 2],
        ];

        foreach ($matriculas as $data) {
            $matricula = Matricula::findOrNew($data['id']);
            $matricula->id = $data['id'];
            $matricula->fill($data);
            $matricula->save();
        }
    }
}
