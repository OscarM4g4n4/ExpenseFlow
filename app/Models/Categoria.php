<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = ['nombre'];

    // Relación: Una Categoría tiene muchos Gastos
    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'categoria_id');
    }
}
