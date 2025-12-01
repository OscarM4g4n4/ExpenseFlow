<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Gasto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gastos';

    protected $fillable = [
        'usuario_id',
        'categoria_id',
        'concepto',
        'monto',
        'fecha',
        'estatus',
        'ruta_comprobante'
    ];

    // Relación: Un Gasto pertenece a un Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación: Un Gasto pertenece a una Categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    // Relación Muchos a Muchos: Un Gasto tiene muchas Etiquetas
    public function etiquetas()
    {
        
        return $this->belongsToMany(Etiqueta::class, 'gasto_etiqueta', 'gasto_id', 'etiqueta_id');
    }
}