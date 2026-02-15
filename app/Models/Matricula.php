<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $fillable = ['usuarios_id', 'curso_id'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuarios_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}
