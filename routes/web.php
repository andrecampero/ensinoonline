<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'root']);

// Profile Update Route (Accessible by all auth users)
Route::group(['middleware' => ['auth']], function () {
    Route::get('/atualizar-dados', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/atualizar-dados', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// Authenticated User Routes (Admin prefix but not exclusively Admin middleware)
Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    // Alunos index is accessible to all auth users (filtered in controller)
    Route::get('/alunos', [App\Http\Controllers\UserController::class, 'index'])->name('alunos.index');

    // Resources accessible to Professors and Admins (Controllers handle specific restrictions)
    Route::resource('professores', App\Http\Controllers\ProfessorController::class);
    Route::resource('cursos', App\Http\Controllers\CursoController::class);
    Route::resource('disciplinas', App\Http\Controllers\DisciplinaController::class);
    Route::resource('matriculas', App\Http\Controllers\MatriculaController::class);

    // Relatórios
    Route::get('relatorios/faixa-etaria', [App\Http\Controllers\RelatorioController::class, 'faixaEtaria'])->name('relatorios.faixa_etaria');
});

// Admin-only Routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('admin.dashboard');

    // Alunos resource (except index which is handled above)
    Route::resource('alunos', App\Http\Controllers\UserController::class)->except(['index']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'homeRedirect'])->middleware('auth');
