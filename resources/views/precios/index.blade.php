@extends('layout.base')

@section('title', 'Gestión de Precios')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Gestión de Precios</h2>
            <p class="text-sm text-gray-500 mt-1">Monitorea precios, calcula IVA y aplica descuentos rápidos.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('productos.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm">
                Volver a Productos
            </a>
            <a href="{{ route('precios.actualizar') }}" class="px-4 py-2 text-sm font-medium text-amber-900 bg-amber-100 border border-amber-200 rounded-lg hover:bg-amber-200 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Actualizar Precios Masivos
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
        <form method="GET" action="{{ route('precios.index') }}" class="flex flex-col md:flex-row items-end gap-4">
            
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar producto</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="w-full pl-10 rounded-lg border-gray-300 focus:ring-gray-900 focus:border-gray-900 text-sm" 
                        placeholder="Nombre del producto...">
                </div>
            </div>

            <div class="pb-2">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="con_descuento" value="1" {{ request('con_descuento') ? 'checked' : '' }}
                        class="rounded border-gray-300 text-gray-900 shadow-sm focus:border-gray-900 focus:ring-gray-900 h-4 w-4">
                    <span class="ml-2 text-sm text-gray-700 font-medium">Solo con descuento</span>
                </label>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition text-sm font-medium">
                    Filtrar
                </button>
                @if(request('search') || request('con_descuento'))
                    <a href="{{ route('precios.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($articulos->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto / Marca</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Venta</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Precio + IVA</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado Desc.</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">% Desc.</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Final</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actualizado</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Acción Rápida</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($articulos as $articulo)
                    @php
                        $precioActual = $articulo->preciosVenta->last();
                        $porcentajeDescuento = 0;
                        if ($precioActual && $precioActual->tieneDescuento && $precioActual->precioVenta > 0) {
                            $porcentajeDescuento = (($precioActual->precioVenta - $precioActual->precioDescuento) / $precioActual->precioVenta) * 100;
                        }
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $articulo->articulo->nombreArticulo }}</div>
                            <div class="text-xs text-gray-500">{{ $articulo->marca->nombreMarca }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-600">
                            @if($precioActual)
                                $ {{ number_format($precioActual->precioVenta, 2) }}
                            @else
                                <span class="text-red-500 text-xs font-bold">Sin precio</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-600">
                            @if($precioActual)
                                $ {{ number_format($precioActual->precioVenta * 1.21, 2) }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($precioActual && $precioActual->tieneDescuento)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Activo
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-500">
                                    Inactivo
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($precioActual && $precioActual->tieneDescuento)
                                <span class="text-sm font-bold text-orange-600">
                                    {{ number_format($porcentajeDescuento, 1) }}%
                                </span>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            @if($precioActual && $precioActual->tieneDescuento)
                                <div class="text-sm font-bold text-green-700">
                                    $ {{ number_format($precioActual->precioDescuento * 1.21, 2) }}
                                </div>
                                <div class="text-xs text-gray-400 line-through">
                                    $ {{ number_format($precioActual->precioVenta * 1.21, 2) }}
                                </div>
                            @elseif($precioActual)
                                <div class="text-sm font-bold text-gray-900">
                                    $ {{ number_format($precioActual->precioVenta * 1.21, 2) }}
                                </div>
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs text-gray-500">
                            @if($precioActual)
                                {{ \Carbon\Carbon::parse($precioActual->fechaActualizacion)->format('d/m/Y') }}
                                <br>
                                {{ \Carbon\Carbon::parse($precioActual->fechaActualizacion)->format('H:i') }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($precioActual && !$precioActual->tieneDescuento)
                                <form action="{{ route('precios.aplicar-descuento', $articulo->idArticuloMarca) }}" method="POST" class="flex items-center justify-end">
                                    @csrf
                                    <div class="flex rounded-md shadow-sm">
                                        <input type="number" name="porcentajeDescuento" step="0.1" min="0" max="100" required 
                                            class="w-20 rounded-l-md border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-xs py-1 px-2"
                                            placeholder="%">
                                        <div class="flex items-center px-2 border border-l-0 border-r-0 border-gray-300 bg-gray-50">
                                            <span class="text-xs text-gray-500">%</span>
                                        </div>
                                        <button type="submit" class="inline-flex items-center px-2 py-1 border border-l-0 border-transparent rounded-r-md shadow-sm text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                                            Aplicar
                                        </button>
                                    </div>
                                </form>
                            @elseif($precioActual && $precioActual->tieneDescuento)
                                <div class="flex flex-col gap-1 items-end">
                                    <form action="{{ route('precios.quitar-descuento', $articulo->idArticuloMarca) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Quitar Desc.
                                        </button>
                                    </form>
                                    <div class="text-xs text-gray-500 text-center w-full">
                                        {{ number_format($porcentajeDescuento, 1) }}% desc.
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $articulos->links() }}
        </div>

        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay precios registrados</h3>
                <p class="mt-1 text-sm text-gray-500">Agrega productos y asignales precios para verlos aquí.</p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validar que el porcentaje no sea mayor a 100%
    document.querySelectorAll('input[name="porcentajeDescuento"]').forEach(function(input) {
        input.addEventListener('change', function() {
            let value = parseFloat(this.value) || 0;
            if (value > 100) {
                this.value = 100;
                alert('El porcentaje de descuento no puede ser mayor al 100%');
            } else if (value < 0) {
                this.value = 0;
            }
        });
    });
});
</script>
@endsection