@extends('layout.base')

@section('title', 'Editar Precio')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Editar Precio</h2>
        <a href="{{ route('precios.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
    </div>

    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900">{{ $articuloMarca->articulo->nombreArticulo }}</h3>
                <div class="mt-1 flex gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $articuloMarca->marca->nombreMarca }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ $articuloMarca->articulo->categoria->nombreCatArticulo ?? 'Sin categoría' }}
                    </span>
                </div>
            </div>
            
            @if($precioActual)
            <div class="text-right">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Precio Actual</p>
                <p class="text-xl font-bold text-gray-900">$ {{ number_format($precioActual->precioVenta, 2) }}</p>
                @php
                    // Convertir fechaActualizacion a Carbon si existe
                    $fechaActualizacion = $precioActual->fechaActualizacion ? 
                        \Carbon\Carbon::parse($precioActual->fechaActualizacion) : null;
                @endphp
                @if($fechaActualizacion)
                    <p class="text-xs text-gray-400">Actualizado: {{ $fechaActualizacion->format('d/m/Y') }}</p>
                @else
                    <p class="text-xs text-gray-400">Fecha no disponible</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-8">
            <form method="POST" action="{{ route('precios.actualizar-individual', $articuloMarca->idArticuloMarca) }}">
                @csrf
                
                <div class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Precio de Venta (Base)</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-lg font-bold">$</span>
                            </div>
                            <input type="number" name="precioVenta" 
                                value="{{ old('precioVenta', $precioActual->precioVenta ?? '') }}" 
                                step="0.01" min="0" required 
                                class="pl-8 text-lg font-medium w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 shadow-sm py-3 transition-colors"
                                placeholder="0.00">
                        </div>
                        @error('precioVenta')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <label class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" name="tieneDescuento" value="1" id="tieneDescuentoCheckbox" 
                                    class="sr-only" 
                                    {{ old('tieneDescuento', $precioActual->tieneDescuento ?? false) ? 'checked' : '' }}>
                                <div class="w-10 h-4 bg-gray-400 rounded-full shadow-inner toggle-bg"></div>
                                <div class="dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition toggle-dot"></div>
                            </div>
                            <span class="ml-3 text-sm font-medium text-gray-900">Activar Precio Promocional (Descuento)</span>
                        </label>

                        <div id="precioDescuentoRow" class="mt-4 hidden transition-all duration-300 ease-in-out">
                            <label class="block text-sm font-medium text-green-700 mb-1">Precio con Descuento</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-green-600 sm:text-lg font-bold">$</span>
                                </div>
                                <input type="number" name="precioDescuento" 
                                    value="{{ old('precioDescuento', $precioActual->precioDescuento ?? '') }}" 
                                    step="0.01" min="0" 
                                    class="pl-8 text-lg font-medium w-full rounded-lg border-green-300 text-green-900 placeholder-green-300 focus:border-green-500 focus:ring-green-500 shadow-sm py-3 transition-colors"
                                    placeholder="0.00">
                            </div>
                            <p class="mt-2 text-xs text-green-600 font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                Este será el precio final visible para el cliente.
                            </p>
                            @error('precioDescuento')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                    <a href="{{ route('precios.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm">
                        Actualizar Precio
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- Estilos para el Toggle Switch personalizado --}}
<style>
    /* Toggle B (Switch estilo iOS) */
    input:checked ~ .dot {
        transform: translateX(100%);
        background-color: #10B981; /* Green-500 */
    }
    input:checked ~ .toggle-bg {
        background-color: #D1FAE5; /* Green-100 */
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const descuentoCheckbox = document.getElementById('tieneDescuentoCheckbox');
    const descuentoRow = document.getElementById('precioDescuentoRow');
    
    function toggleDescuentoField() {
        if (descuentoCheckbox.checked) {
            descuentoRow.classList.remove('hidden');
            // Animación simple de entrada
            descuentoRow.classList.add('animate-fade-in-down');
            const input = descuentoRow.querySelector('input');
            if(input && !input.value) input.focus();
        } else {
            descuentoRow.classList.add('hidden');
        }
    }
    
    // Estado inicial
    toggleDescuentoField();
    
    // Cambiar cuando se modifique el checkbox
    descuentoCheckbox.addEventListener('change', toggleDescuentoField);
});
</script>
@endsection