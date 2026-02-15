<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Perfil;
use App\Models\Curso;
use App\Models\Matricula;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatriculaTest extends TestCase
{
   use RefreshDatabase;

   protected function setUp(): void
   {
      parent::setUp();

      // Setup initial data
      Perfil::updateOrCreate(['id' => 1], ['nome_perfil' => 'Admin']);
      Perfil::updateOrCreate(['id' => 3], ['nome_perfil' => 'Aluno']);
   }

   public function test_admin_can_enroll_student_in_course()
   {
      $admin = User::create([
         'name' => 'Admin User',
         'email' => 'admin@test.com',
         'password' => bcrypt('password'),
         'perfil_id' => 1
      ]);

      $aluno = User::create([
         'name' => 'Aluno Teste',
         'email' => 'aluno@test.com',
         'password' => bcrypt('password'),
         'perfil_id' => 3
      ]);

      $curso = Curso::create([
         'titulo' => 'Curso de Teste',
         'area' => 'TI',
         'descricao' => 'Descricao teste',
         'data_inicio' => '2026-01-01',
         'data_fim' => '2026-12-31'
      ]);

      $response = $this->actingAs($admin)->post('/admin/matriculas', [
         'usuarios_id' => $aluno->id,
         'curso_id' => $curso->id
      ]);

      $response->assertRedirect();
      $this->assertDatabaseHas('matriculas', [
         'usuarios_id' => $aluno->id,
         'curso_id' => $curso->id
      ]);
   }

   public function test_cannot_enroll_same_student_twice_in_same_course()
   {
      $admin = User::create([
         'name' => 'Admin User',
         'email' => 'admin@test.com',
         'password' => bcrypt('password'),
         'perfil_id' => 1
      ]);

      $aluno = User::create([
         'name' => 'Aluno Teste',
         'email' => 'aluno@test.com',
         'password' => bcrypt('password'),
         'perfil_id' => 3
      ]);

      $curso = Curso::create([
         'titulo' => 'Curso de Teste',
         'area' => 'TI',
         'descricao' => 'Descricao teste',
         'data_inicio' => '2026-01-01',
         'data_fim' => '2026-12-31'
      ]);

      // First enrollment
      Matricula::create([
         'usuarios_id' => $aluno->id,
         'curso_id' => $curso->id
      ]);

      // Attempt second enrollment
      $response = $this->actingAs($admin)->post('/admin/matriculas', [
         'usuarios_id' => $aluno->id,
         'curso_id' => $curso->id
      ]);

      $response->assertSessionHas('error', 'O aluno já está matriculado neste curso!');
      $this->assertEquals(1, Matricula::where('usuarios_id', $aluno->id)->where('curso_id', $curso->id)->count());
   }
}
