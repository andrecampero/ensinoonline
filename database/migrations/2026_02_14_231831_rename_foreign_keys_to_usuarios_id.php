<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('disciplinas', function (Blueprint $table) {
            $table->renameColumn('professor_id', 'usuarios_id');
        });

        Schema::table('matriculas', function (Blueprint $table) {
            $table->renameColumn('user_id', 'usuarios_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disciplinas', function (Blueprint $table) {
            $table->renameColumn('usuarios_id', 'professor_id');
        });

        Schema::table('matriculas', function (Blueprint $table) {
            $table->renameColumn('usuarios_id', 'user_id');
        });
    }
};
