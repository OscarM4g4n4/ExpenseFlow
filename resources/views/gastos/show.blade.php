<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Gasto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-bold mb-4">Información General</h3>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm">Concepto:</p>
                                <p class="text-xl font-bold">{{ $gasto->concepto }}</p>
                            </div>

                            <div class="mb-4">
                                <p class="text-gray-600 text-sm">Monto:</p>
                                <p class="text-xl text-green-600 font-bold">${{ number_format($gasto->monto, 2) }}</p>
                            </div>

                            <div class="mb-4">
                                <p class="text-gray-600 text-sm">Categoría:</p>
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
                                    {{ $gasto->categoria->nombre ?? 'N/A' }}
                                </span>
                            </div>

                            <div class="mb-4">
                                <p class="text-gray-600 text-sm">Etiquetas:</p>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @foreach($gasto->etiquetas as $etiqueta)
                                        <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full">
                                            {{ $etiqueta->nombre }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <a href="{{ route('gastos.index') }}" class="text-blue-500 hover:underline">
                                    &larr; Volver al listado
                                </a>
                            </div>
                        </div>

                        <div class="border-l pl-6">
                            <h3 class="text-lg font-bold mb-4">Comprobante</h3>
                            
                            @if($gasto->ruta_comprobante)
                                <div class="border rounded-lg p-2 shadow-sm">
                                    @if(Str::endsWith($gasto->ruta_comprobante, ['.jpg', '.jpeg', '.png']))
                                        <img src="{{ asset('storage/' . $gasto->ruta_comprobante) }}" 
                                             alt="Comprobante" 
                                             class="max-w-full h-auto rounded">
                                    
                                    @else
                                        <div class="text-center py-10">
                                            <p class="mb-4 text-gray-500">Archivo disponible para descarga</p>
                                            <a href="{{ asset('storage/' . $gasto->ruta_comprobante) }}" target="_blank"
                                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                Ver / Descargar Archivo
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <p class="text-gray-500 italic">No hay comprobante adjunto.</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>