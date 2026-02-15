<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    protected $fillable = ['titulo', 'descricao', 'curso_id', 'usuarios_id'];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuarios_id');
    }
}
