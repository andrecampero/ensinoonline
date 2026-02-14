<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration {
   /**
    * Run the migrations.
    */
   public function up(): void
   {
      // 1. Criar tabela perfis
      Schema::create('perfis', function (Blueprint $table) {
         $table->id();
         $table->string('nome_perfil');
         $table->timestamps();
      });

      // Inserir perfis padrão
      DB::table('perfis')->insert([
         ['id' => 1, 'nome_perfil' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
         ['id' => 2, 'nome_perfil' => 'Professor', 'created_at' => now(), 'updated_at' => now()],
         ['id' => 3, 'nome_perfil' => 'Aluno', 'created_at' => now(), 'updated_at' => now()],
      ]);

      // 2. Adicionar perfil_id na tabela users
      Schema::table('users', function (Blueprint $table) {
         $table->foreignId('perfil_id')->nullable()->after('id')->constrained('perfis');
      });

      // 3. Migrar usuários existentes (role string -> perfil_id)
      $users = DB::table('users')->get();
      foreach ($users as $user) {
         $perfilId = 3; // Default Aluno
         if (($user->role ?? '') === 'admin') {
            $perfilId = 1;
         } elseif (($user->role ?? '') === 'professor') {
            $perfilId = 2;
         } elseif (($user->role ?? '') === 'aluno') {
            $perfilId = 3;
         }

         DB::table('users')->where('id', $user->id)->update(['perfil_id' => $perfilId]);
      }

      // Agora tornar perfil_id obrigatório
      Schema::table('users', function (Blueprint $table) {
         $table->foreignId('perfil_id')->nullable(false)->change();
      });

      // 4. Migrar Professores (da tabela professores para users)

      // Adicionar coluna temporária em disciplinas para o novo ID
      Schema::table('disciplinas', function (Blueprint $table) {
         $table->unsignedBigInteger('new_professor_id')->nullable()->after('professor_id');
      });

      $professoresAntigos = DB::table('professores')->get();

      foreach ($professoresAntigos as $prof) {
         // Verificar se já existe user com esse email
         $existingUser = DB::table('users')->where('email', $prof->email)->first();

         $newUserId = null;

         if ($existingUser) {
            // Se user já existe, usamos ele. Atualizamos perfil se necessário.
            $newUserId = $existingUser->id;
            if ($existingUser->perfil_id !== 1) { // Se não for admin
               DB::table('users')->where('id', $newUserId)->update(['perfil_id' => 2]);
            }
         } else {
            // Criar novo user para o professor
            $newUserId = DB::table('users')->insertGetId([
               'name' => $prof->nome,
               'email' => $prof->email,
               'password' => Hash::make('Mudar@@123'),
               'perfil_id' => 2, // Professor
               'created_at' => now(),
               'updated_at' => now(),
               // A coluna role ainda existe, mas vamos remover depois.
               // Para garantir consistência temporária, populamos.
               'role' => 'aluno' // Placeholder value to satisfy enum constraint ['admin', 'aluno'] temporarily
            ]);
         }

         // Atualizar a coluna TEMPORÁRIA nas disciplinas deste professor antigo
         DB::table('disciplinas')
            ->where('professor_id', $prof->id)
            ->update(['new_professor_id' => $newUserId]);
      }

      // 5. Troca de colunas em disciplinas
      Schema::table('disciplinas', function (Blueprint $table) {
         // Remover FK antiga
         $table->dropForeign(['professor_id']);
         // Remover coluna antiga
         $table->dropColumn('professor_id');
      });

      Schema::table('disciplinas', function (Blueprint $table) {
         // Renomear a nova coluna para o nome padrão
         $table->renameColumn('new_professor_id', 'professor_id');
      });

      Schema::table('disciplinas', function (Blueprint $table) {
         // Adicionar nova FK apontando para users
         $table->foreign('professor_id')->references('id')->on('users')->onDelete('cascade');
      });

      // 6. Remover tabela professores e coluna role de users
      Schema::dropIfExists('professores');

      Schema::table('users', function (Blueprint $table) {
         $table->dropColumn('role');
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      // Down method omitted for brevity as this is a destructive restructuring
   }
};
