@extends('layout.base')

@section('title', 'Actualizar Precios Masivos')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Actualización Masiva</h2>
            <p class="text-sm text-gray-500 mt-1">Edita múltiples precios simultáneamente.</p>
        </div>
        <a href="{{ route('precios.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4">
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4">
            <p class="text-red-700 font-bold mb-2">Hubo problemas con la actualización:</p>
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('precios.store') }}">
        @csrf
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Producto</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Actual</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nuevo Precio</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">¿Descuento?</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">% Descuento</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Oferta</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($articulos as $index => $articulo)
                        @php
                            $precioActual = $articulo->preciosVenta->sortByDesc('fechaActualizacion')->first();
                            $porcentajeDescuento = 0;
                            if ($precioActual && $precioActual->tieneDescuento && $precioActual->precioVenta > 0) {
                                $porcentajeDescuento = (($precioActual->precioVenta - $precioActual->precioDescuento) / $precioActual->precioVenta) * 100;
                            }
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $articulo->articulo->nombreArticulo }}</div>
                                <div class="text-xs text-gray-500">{{ $articulo->marca->nombreMarca }}</div>
                                <input type="hidden" name="precios[{{ $index }}][idArticuloMarca]" value="{{ $articulo->idArticuloMarca }}">
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                @if($precioActual)
                                    $ {{ number_format($precioActual->precioVenta, 2) }}
                                @else
                                    <span class="text-red-400 text-xs">--</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="relative rounded-md shadow-sm max-w-[140px] mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="precios[{{ $index }}][precioVenta]" 
                                        id="precioVenta-{{ $index }}"
                                        step="0.01" min="0" 
                                        value="{{ $precioActual ? $precioActual->precioVenta : 0 }}" 
                                        required
                                        class="precio-venta-input pl-7 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-1.5"
                                        data-index="{{ $index }}">
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="checkbox" name="precios[{{ $index }}][tieneDescuento]" value="1" 
                                    {{ $precioActual && $precioActual->tieneDescuento ? 'checked' : '' }}
                                    class="descuento-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 h-5 w-5 cursor-pointer"
                                    data-index="{{ $index }}">
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="relative rounded-md shadow-sm max-w-[140px] mx-auto">
                                    <input type="number" name="precios[{{ $index }}][porcentajeDescuento]" 
                                        id="porcentajeDescuento-{{ $index }}"
                                        step="0.01" min="0" max="100" 
                                        value="{{ number_format($porcentajeDescuento, 2) }}" 
                                        placeholder="0.00"
                                        class="porcentaje-descuento-input pl-10 w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500 sm:text-sm py-1.5 transition-colors duration-200 disabled:bg-gray-100 disabled:text-gray-400 disabled:border-gray-200"
                                        data-index="{{ $index }}"
                                        {{ $precioActual && $precioActual->tieneDescuento ? '' : 'disabled' }}>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-orange-600 sm:text-sm">%</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="relative rounded-md shadow-sm max-w-[140px] mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-green-600 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="precios[{{ $index }}][precioDescuento]" 
                                        id="precioDescuento-{{ $index }}"
                                        step="0.01" min="0" 
                                        value="{{ $precioActual && $precioActual->tieneDescuento ? $precioActual->precioDescuento : '' }}" 
                                        placeholder="0.00"
                                        class="precio-descuento-input pl-7 w-full rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500 sm:text-sm py-1.5 transition-colors duration-200 disabled:bg-gray-100 disabled:text-gray-400 disabled:border-gray-200"
                                        data-index="{{ $index }}"
                                        {{ $precioActual && $precioActual->tieneDescuento ? '' : 'disabled' }}
                                        readonly>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                <p class="text-xs text-gray-500">
                    <span class="font-bold text-gray-700">Nota:</span> Se mantendrá el historial de todos los precios anteriores.
                </p>
                <div class="flex gap-3">
                    <a href="{{ route('precios.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Guardar Todos los Cambios
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar todos los checkboxes
    const checkboxes = document.querySelectorAll('.descuento-checkbox');
    
    // Función para calcular el precio de descuento
    function calcularPrecioDescuento(index) {
        const precioVenta = parseFloat(document.getElementById('precioVenta-' + index).value) || 0;
        const porcentajeDescuento = parseFloat(document.getElementById('porcentajeDescuento-' + index).value) || 0;
        
        if (precioVenta > 0 && porcentajeDescuento > 0) {
            const descuento = precioVenta * (porcentajeDescuento / 100);
            const precioDescuento = precioVenta - descuento;
            document.getElementById('precioDescuento-' + index).value = precioDescuento.toFixed(2);
        } else {
            document.getElementById('precioDescuento-' + index).value = '';
        }
    }

    // Función para habilitar/deshabilitar inputs de descuento
    function toggleInput(checkbox) {
        const index = checkbox.getAttribute('data-index');
        const porcentajeInput = document.getElementById('porcentajeDescuento-' + index);
        const precioDescuentoInput = document.getElementById('precioDescuento-' + index);
        
        if (checkbox.checked) {
            porcentajeInput.disabled = false;
            precioDescuentoInput.disabled = false;
            porcentajeInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
            precioDescuentoInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
            porcentajeInput.classList.add('bg-white');
            precioDescuentoInput.classList.add('bg-white');
            
            // Calcular precio descuento inicial
            calcularPrecioDescuento(index);
            
            if(porcentajeInput.value === '' || porcentajeInput.value === '0.00') {
                porcentajeInput.focus();
            }
        } else {
            porcentajeInput.disabled = true;
            precioDescuentoInput.disabled = true;
            porcentajeInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            precioDescuentoInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            porcentajeInput.classList.remove('bg-white');
            precioDescuentoInput.classList.remove('bg-white');
            porcentajeInput.value = '';
            precioDescuentoInput.value = '';
        }
    }

    // Inicializar y agregar listeners
    checkboxes.forEach(function(checkbox) {
        // Estado inicial
        toggleInput(checkbox);
        
        // Evento change
        checkbox.addEventListener('change', function() {
            toggleInput(this);
        });
    });

    // Event listeners para cambios en precio de venta y porcentaje de descuento
    document.querySelectorAll('.precio-venta-input, .porcentaje-descuento-input').forEach(function(input) {
        input.addEventListener('input', function() {
            const index = this.getAttribute('data-index');
            calcularPrecioDescuento(index);
        });
    });

    // Event listener para validar porcentaje (no más de 100%)
    document.querySelectorAll('.porcentaje-descuento-input').forEach(function(input) {
        input.addEventListener('change', function() {
            let value = parseFloat(this.value) || 0;
            if (value > 100) {
                this.value = 100;
            } else if (value < 0) {
                this.value = 0;
            }
            const index = this.getAttribute('data-index');
            calcularPrecioDescuento(index);
        });
    });
});
</script>
@endsection