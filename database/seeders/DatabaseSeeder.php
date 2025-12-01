<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// No necesitamos imports extras si usamos las rutas completas como abajo

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear el usuario ADMIN (Con el que vas a entrar)
        \App\Models\User::factory()->create([
            'nombre' => 'Oscar Admin',
            'correo' => 'admin@test.com',
            'password' => bcrypt('password'), // La contraseña será 'password'
            'rol' => 'admin',
        ]);

        // 2. Crear 10 empleados extra
        \App\Models\User::factory(10)->create();

        // 3. Crear Categorías
        \App\Models\Categoria::factory(5)->create();
        
        // 4. Crear Etiquetas
        \App\Models\Etiqueta::factory(5)->create();

        // 5. Crear Gastos
        \App\Models\Gasto::factory(20)->create();
    }
}