<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gasto>
 */
class GastoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'concepto' => fake()->sentence(3), 
        'monto' => fake()->randomFloat(2, 10, 5000), 
        'fecha' => fake()->dateTimeBetween('-3 months', 'now'), 
        'estatus' => fake()->randomElement(['pendiente', 'aprobado', 'rechazado']),
        'ruta_comprobante' => 'comprobantes/ejemplo.jpg', 
        
       
        'usuario_id' => \App\Models\User::inRandomOrder()->first()->id ?? 1,
        'categoria_id' => \App\Models\Categoria::inRandomOrder()->first()->id ?? 1,
        ];
    }
}
