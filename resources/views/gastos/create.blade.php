<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nuevo Gasto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('gastos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="concepto">
                                Concepto
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                id="concepto" type="text" name="concepto" value="{{ old('concepto') }}" required>
                            @error('concepto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="monto">
                                    Monto ($)
                                </label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                    id="monto" type="number" step="0.01" name="monto" value="{{ old('monto') }}" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="fecha">
                                    Fecha del Gasto
                                </label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                    id="fecha" type="date" name="fecha" value="{{ old('fecha') }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="categoria_id">
                                Categoría
                            </label>
                            <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                name="categoria_id" id="categoria_id">
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Etiquetas
                            </label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($etiquetas as $tag)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="etiquetas[]" value="{{ $tag->id }}" class="form-checkbox h-5 w-5 text-blue-600">
                                        <span class="ml-2 text-gray-700">{{ $tag->nombre }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="comprobante">
                                Comprobante (Imagen o PDF)
                            </label>
                            <input type="file" name="comprobante" id="comprobante" class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100" required>
                            @error('comprobante') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-4">
    
    <a href="{{ route('gastos.index') }}" class="text-gray-600 hover:text-gray-900 underline">
        Cancelar
    </a>

    <button type="submit" 
            class="bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 py-2 px-4">
        Guardar Gasto
    </button>
</div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
