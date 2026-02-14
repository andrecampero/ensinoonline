<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@ensino.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Aluno (Student) user from specification (Emanuel)
        User::updateOrCreate(
            ['email' => 'emanuel@ensino.com'],
            [
                'name' => 'Emanuel Aluno',
                'password' => bcrypt('password'),
                'role' => 'aluno',
                'data_nascimento' => '2000-01-01',
            ]
        );

        // Seed some initial courses and professors for easy testing
        $professor = \App\Models\Professor::updateOrCreate(
            ['email' => 'jubilut@ensino.com'],
            ['nome' => 'Prof. Jubilut']
        );

        $curso = \App\Models\Curso::updateOrCreate(
            ['titulo' => 'Biologia Total'],
            [
                'area' => 'Ciências Biológicas',
                'descricao' => 'Curso completo de biologia para vestibulares.',
                'data_inicio' => now(),
                'data_fim' => now()->addMonths(6),
            ]
        );

        \App\Models\Disciplina::updateOrCreate(
            ['titulo' => 'Genética'],
            [
                'descricao' => 'Estudo dos genes e hereditariedade.',
                'curso_id' => $curso->id,
                'professor_id' => $professor->id,
            ]
        );
    }
}
