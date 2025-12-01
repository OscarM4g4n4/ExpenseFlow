<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // ESTO ES VITAL: Le decimos que la tabla se llama 'usuarios'
    protected $table = 'usuarios'; 

    protected $fillable = [
        'nombre',
        'correo',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relación con gastos
    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'usuario_id');
    }
}