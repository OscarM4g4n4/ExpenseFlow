<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\Categoria;
use App\Models\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastoController extends Controller
{
    /**
     * Muestra el listado de gastos.
     */
    public function index()
    {
        // Eager Loading para optimizar consultas (N+1)
        $gastos = Gasto::where('usuario_id', Auth::id())
                    ->with(['categoria', 'etiquetas'])
                    ->latest()
                    ->get();

        return view('gastos.index', compact('gastos'));
    }

    /**
     * Muestra el formulario para crear un nuevo gasto.
     */
    public function create()
    {
        $categorias = Categoria::all();
        $etiquetas = Etiqueta::all();
        
        return view('gastos.create', compact('categorias', 'etiquetas'));
    }

    /**
     * Guarda el nuevo gasto en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. VALIDACIÓN
        $request->validate([
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:1',
            'fecha' => 'required|date',
            'categoria_id' => 'required|exists:categorias,id',
            'comprobante' => 'required|file|mimes:jpg,png,pdf|max:2048', // Max 2MB
        ]);

        // 2. SUBIDA DE ARCHIVO
        $rutaArchivo = null;
        if ($request->hasFile('comprobante')) {
            $rutaArchivo = $request->file('comprobante')->store('comprobantes', 'public');
        }

        // 3. GUARDAR EN BD
        $gasto = Gasto::create([
            'usuario_id' => Auth::id(),
            'categoria_id' => $request->categoria_id,
            'concepto' => $request->concepto,
            'monto' => $request->monto,
            'fecha' => $request->fecha,
            'ruta_comprobante' => $rutaArchivo,
            'estatus' => 'pendiente'
        ]);

        // 4. RELACIÓN MUCHOS A MUCHOS (Etiquetas)
        if ($request->has('etiquetas')) {
            $gasto->etiquetas()->attach($request->etiquetas);
        }

        // 5. REDIRECCIONAR
        return redirect()->route('gastos.index')->with('success', '¡Gasto registrado exitosamente!');
    }

    /**
     * Muestra un gasto específico.
     */
    public function show(Gasto $gasto)
    {
        if ($gasto->usuario_id !== Auth::id()) {
        abort(403);
    }

    return view('gastos.show', compact('gasto'));
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Gasto $gasto)
    {
        if ($gasto->usuario_id !== Auth::id()) {
            abort(403);
        }

        $categorias = Categoria::all();
        $etiquetas = Etiqueta::all();
        
        return view('gastos.edit', compact('gasto', 'categorias', 'etiquetas'));
    }

    /**
     * Actualiza el gasto en la base de datos.
     */
    public function update(Request $request, Gasto $gasto)
    {
        if ($gasto->usuario_id !== Auth::id()) {
            abort(403);
        }

        // 2. Validación 
        $request->validate([
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:1',
            'fecha' => 'required|date',
            'categoria_id' => 'required|exists:categorias,id',
            'comprobante' => 'nullable|file|mimes:jpg,png,pdf|max:2048', // Nullable: No es obligatorio subir foto nueva
        ]);

        // 3. Manejo de Archivo (Si subieron uno nuevo)
        if ($request->hasFile('comprobante')) {
            
            
            $rutaArchivo = $request->file('comprobante')->store('comprobantes', 'public');
            $gasto->ruta_comprobante = $rutaArchivo;
        }

        // 4. Actualizar datos
        $gasto->update([
            'categoria_id' => $request->categoria_id,
            'concepto' => $request->concepto,
            'monto' => $request->monto,
            'fecha' => $request->fecha,
            
        ]);

        // 5. Actualizar Etiquetas (Sincronizar)
        if ($request->has('etiquetas')) {
            $gasto->etiquetas()->sync($request->etiquetas); // sync: Borra las viejas y pone las nuevas
        } else {
            $gasto->etiquetas()->detach(); // Si desmarcó todas, quitamos las relaciones
        }

        return redirect()->route('gastos.index')->with('success', '¡Gasto actualizado correctamente!');
    }

    /**
     * Elimina el gasto.
     */
    public function destroy(Gasto $gasto)
{
    // 1. Verificar dueño
    if ($gasto->usuario_id !== Auth::id()) {
        abort(403);
    }

    // 2. Borrar (Soft Delete)
    $gasto->etiquetas()->detach();
    $gasto->delete();

    // 3. Redirigir 
    return redirect()->route('gastos.index')->with('success', '¡Gasto eliminado correctamente!');
}
}