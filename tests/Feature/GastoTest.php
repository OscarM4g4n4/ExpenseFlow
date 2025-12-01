<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Gasto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GastoTest extends TestCase
{
    // Usamos RefreshDatabase para que cada prueba limpie la BD y empiece de cero
    use RefreshDatabase;

    /**
     * Prueba 1: Siendo usuario X, al consultar ruta Y, aseguro código 200.
     */
    public function test_usuario_puede_ver_listado_gastos()
    {
        // 1. Crear un usuario falso
        $user = User::factory()->create();

        // 2. Actuar como ese usuario (Login) y entrar a la ruta
        $response = $this->actingAs($user)->get('/gastos');

        // 3. Asegurar que cargó bien (Status 200)
        $response->assertStatus(200);
        
        // Extra: Verificar que ve el título "Mis Gastos"
        $response->assertSee('Mis Gastos');
    }

    /**
     * Prueba 2: Siendo usuario X, al enviar petición POST, aseguro creación en DB.
     */
    public function test_usuario_puede_crear_un_gasto()
    {
        // 1. Preparar datos
        $user = User::factory()->create();
        $categoria = Categoria::factory()->create(); // Necesitamos una categoría
        Storage::fake('public'); // Simulamos el disco duro para no guardar archivos reales
        $archivo = UploadedFile::fake()->image('ticket.jpg'); // Simulamos una foto

        // 2. Enviar petición POST como si llenáramos el formulario
        $response = $this->actingAs($user)->post('/gastos', [
            'concepto' => 'Gasto de Prueba Testing',
            'monto' => 500.00,
            'fecha' => '2025-11-30',
            'categoria_id' => $categoria->id,
            'comprobante' => $archivo,
        ]);

        // 3. Verificaciones
        $response->assertRedirect(route('gastos.index')); // Que nos regrese al inicio
        
        // Verificar que existe en la base de datos
        $this->assertDatabaseHas('gastos', [
            'concepto' => 'Gasto de Prueba Testing',
            'monto' => 500.00,
            'usuario_id' => $user->id,
        ]);
    }

    /**
     * Prueba 3: Siendo usuario X, al enviar petición con info faltante, asegurar error.
     */
    public function test_crear_gasto_requiere_validacion()
    {
        $user = User::factory()->create();

        // Enviamos un arreglo vacío (sin datos)
        $response = $this->actingAs($user)->post('/gastos', []);

        // Aseguramos que haya errores en la sesión para estos campos
        $response->assertSessionHasErrors(['concepto', 'monto', 'fecha', 'categoria_id']);
    }

    /**
     * Prueba 4: Siendo usuario X, al enviar petición DELETE, aseguro eliminación.
     */
    public function test_usuario_puede_borrar_gasto()
    {
        // 1. Crear un usuario y un gasto asociado a él
        $user = User::factory()->create();
        $categoria = Categoria::factory()->create();
        
        $gasto = Gasto::factory()->create([
            'usuario_id' => $user->id,
            'categoria_id' => $categoria->id,
            'ruta_comprobante' => 'fake.jpg'
        ]);

        // 2. Enviar petición DELETE
        $response = $this->actingAs($user)->delete("/gastos/{$gasto->id}");

        // 3. Verificar redirección
        $response->assertRedirect(route('gastos.index'));

        // 4. Verificar Soft Delete (Que la columna deleted_at NO sea null)
        // Esto verifica que el registro sigue ahí pero está marcado como borrado
        $this->assertSoftDeleted('gastos', [
            'id' => $gasto->id,
        ]);
    }
}