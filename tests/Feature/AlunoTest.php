<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Perfil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlunoTest extends TestCase
{
   use RefreshDatabase;

   protected function setUp(): void
   {
      parent::setUp();

      // Setup initial profiles
      Perfil::updateOrCreate(['id' => 1], ['nome_perfil' => 'Admin']);
      Perfil::updateOrCreate(['id' => 3], ['nome_perfil' => 'Aluno']);
   }

   public function test_admin_can_register_new_student()
   {
      $admin = User::create([
         'name' => 'Admin User',
         'email' => 'admin@test.com',
         'password' => bcrypt('password'),
         'perfil_id' => 1
      ]);

      $response = $this->actingAs($admin)->post('/admin/alunos', [
         'name' => 'Novo Aluno',
         'email' => 'aluno@novo.com',
         'data_nascimento' => '2005-05-15'
      ]);

      $response->assertRedirect();
      $this->assertDatabaseHas('usuarios', [
         'name' => 'Novo Aluno',
         'email' => 'aluno@novo.com',
         'perfil_id' => 3
      ]);
   }

   public function test_cannot_register_student_with_existing_email()
   {
      $admin = User::create([
         'name' => 'Admin User',
         'email' => 'admin@test.com',
         'password' => bcrypt('password'),
         'perfil_id' => 1
      ]);

      User::create([
         'name' => 'Existing Aluno',
         'email' => 'aluno@existente.com',
         'password' => bcrypt('password'),
         'perfil_id' => 3
      ]);

      $response = $this->actingAs($admin)->post('/admin/alunos', [
         'name' => 'Outro Aluno',
         'email' => 'aluno@existente.com',
         'data_nascimento' => '2005-05-15'
      ]);

      $response->assertSessionHasErrors('email');
   }
}
