<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    protected $fillable = ['titulo', 'descricao', 'curso_id', 'professor_id'];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }
}
