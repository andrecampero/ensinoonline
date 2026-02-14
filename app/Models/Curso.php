<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $fillable = ['titulo', 'area', 'descricao', 'data_inicio', 'data_fim'];

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }
}
