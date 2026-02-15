<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'perfil_id',
        'data_nascimento',
        // 'role' removed from fillable, handled by perfil relationship
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'usuarios_id');
    }

    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }

    // Accessor for backward compatibility with $user->role
    public function getRoleAttribute()
    {
        if ($this->relationLoaded('perfil')) {
            return strtolower($this->perfil->nome_perfil);
        }

        // Fallback or lazy load
        return $this->perfil ? strtolower($this->perfil->nome_perfil) : null;
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isProfessor()
    {
        return $this->role === 'professor';
    }

    public function isAluno()
    {
        return $this->role === 'aluno';
    }
}
