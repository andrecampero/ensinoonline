<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use Carbon\Carbon;

class RelatorioController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth');
   }

   public function faixaEtaria()
   {
      // Apenas professores e admins podem ver
      if (auth()->user()->isAluno()) {
         abort(403, 'Acesso não autorizado.');
      }

      $dados = [];
      // Carrega cursos com matrículas e usuários (alunos)
      $cursos = Curso::with(['matriculas.usuario'])->get();

      foreach ($cursos as $curso) {
         // Filtra usuários válidos e com data de nascimento preenchida
         $alunos = $curso->matriculas->map(function ($matricula) {
            return $matricula->usuario;
         })->filter(function ($user) {
            return $user && $user->data_nascimento;
         });

         if ($alunos->isEmpty()) {
            $dados[] = [
               'curso' => $curso->titulo,
               'media_idade' => null, // Para ordenação correta se necessário, ou '-'
               'mais_novo' => '-',
               'mais_velho' => '-'
            ];
            continue;
         }

         $idades = $alunos->map(function ($user) {
            return Carbon::parse($user->data_nascimento)->age;
         });

         $media = $idades->avg();

         // Mais novo = maior data de nascimento
         $alunoMaisNovo = $alunos->sortByDesc('data_nascimento')->first();
         $idadeMaisNovo = Carbon::parse($alunoMaisNovo->data_nascimento)->age;

         // Mais velho = menor data de nascimento
         $alunoMaisVelho = $alunos->sortBy('data_nascimento')->first();
         $idadeMaisVelho = Carbon::parse($alunoMaisVelho->data_nascimento)->age;

         $dados[] = [
            'curso' => $curso->titulo,
            'media_idade' => number_format($media, 1),
            'mais_novo' => $alunoMaisNovo->name . ' (' . $idadeMaisNovo . ' anos)',
            'mais_velho' => $alunoMaisVelho->name . ' (' . $idadeMaisVelho . ' anos)',
         ];
      }

      return view('admin.relatorios.faixa_etaria', compact('dados'));
   }
}
