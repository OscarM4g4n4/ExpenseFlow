<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etiqueta extends Model
{
    use HasFactory;

    protected $table = 'etiquetas';

    protected $fillable = ['nombre'];

    // Relación Muchos a Muchos con Gastos
    public function gastos()
    {
        return $this->belongsToMany(Gasto::class, 'gasto_etiqueta', 'etiqueta_id', 'gasto_id');
    }
}


