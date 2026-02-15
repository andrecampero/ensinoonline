<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Perfil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
   use RefreshDatabase;

   protected function setUp(): void
   {
      parent::setUp();

      // Setup initial profiles
      Perfil::updateOrCreate(['id' => 1], ['nome_perfil' => 'Admin']);
      Perfil::updateOrCreate(['id' => 2], ['nome_perfil' => 'Professor']);
      Perfil::updateOrCreate(['id' => 3], ['nome_perfil' => 'Aluno']);
   }

   public function test_user_can_login_with_correct_credentials()
   {
      $user = User::create([
         'name' => 'Test User',
         'email' => 'test@example.com',
         'password' => Hash::make('password123'),
         'perfil_id' => 1
      ]);

      $response = $this->post('/login', [
         'email' => 'test@example.com',
         'password' => 'password123',
      ]);

      $response->assertRedirect();
      $this->assertAuthenticatedAs($user);
   }

   public function test_user_cannot_login_with_incorrect_password()
   {
      $user = User::create([
         'name' => 'Test User',
         'email' => 'test@example.com',
         'password' => Hash::make('password123'),
         'perfil_id' => 1
      ]);

      $response = $this->post('/login', [
         'email' => 'test@example.com',
         'password' => 'wrong-password',
      ]);

      $response->assertSessionHasErrors('email');
      $this->assertGuest();
   }
}
