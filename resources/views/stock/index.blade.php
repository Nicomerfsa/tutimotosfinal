@extends('layout.base')

@section('title', 'Gestión de Stock')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Gestión de Stock</h2>
            <p class="text-sm text-gray-500 mt-1">Control de inventario físico y valorizado.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('stock.bajo') }}" class="px-4 py-2 text-sm font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Ver Alertas
            </a>
            
            <div class="flex rounded-md shadow-sm" role="group">
                <a href="{{ route('movimientos.entrada') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-50 hover:text-green-600 transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    Entrada
                </a>
                <a href="{{ route('movimientos.salida') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b border-r border-gray-300 rounded-r-lg hover:bg-gray-50 hover:text-red-600 transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    Salida
                </a>
            </div>

            <a href="{{ route('precios.actualizar') }}" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Precios
            </a>
        </div>
    </div>

    @if($stock->count() > 0)
        @php
            $valorTotalInventario = $stock->sum(function($item) {
                $precioActual = $item->articuloMarca->preciosVenta->sortByDesc('fechaActualizacion')->first();
                $precio = $precioActual ? ($precioActual->tieneDescuento && $precioActual->precioDescuento ? $precioActual->precioDescuento : $precioActual->precioVenta) : 0;
                return $item->cantidadActual * $precio;
            });
        @endphp
        <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-xl shadow-md p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider font-medium">Valor Total del Inventario</p>
                    <h3 class="text-3xl font-bold mt-1">$ {{ number_format($valorTotalInventario, 2) }}</h3>
                    <p class="text-gray-400 text-xs mt-2">* Calculado en base al precio de venta actual.</p>
                </div>
                <div class="hidden md:block p-4 bg-white/10 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 36v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
        <form method="GET" action="{{ route('stock.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            
            <div class="md:col-span-4">
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

            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Almacén</label>
                <select name="almacen" class="w-full rounded-lg border-gray-300 focus:ring-gray-900 focus:border-gray-900 text-sm">
                    <option value="">Todos los almacenes</option>
                    @foreach($almacenes as $almacen)
                        <option value="{{ $almacen->idAlmacen }}" {{ request('almacen') == $almacen->idAlmacen ? 'selected' : '' }}>
                            {{ $almacen->nombreAlmacen }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                <select name="categoria" class="w-full rounded-lg border-gray-300 focus:ring-gray-900 focus:border-gray-900 text-sm">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->idCatArticulo }}" {{ request('categoria') == $categoria->idCatArticulo ? 'selected' : '' }}>
                            {{ $categoria->nombreCatArticulo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="w-full bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition text-sm font-medium shadow-sm">
                    Filtrar
                </button>
                @if(request('search') || request('almacen') || request('categoria') || request('stock_bajo'))
                    <a href="{{ route('stock.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm font-medium text-center">
                        X
                    </a>
                @endif
            </div>

            <div class="md:col-span-12">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="stock_bajo" value="1" {{ request('stock_bajo') ? 'checked' : '' }} 
                        class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 h-4 w-4">
                    <span class="ml-2 text-sm text-gray-700">Mostrar solo productos con stock bajo (&lt; 10)</span>
                </label>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        @if($stock->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto / Marca</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Disponibilidad</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unit.</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($stock as $item)
                    @php
                        $precioActual = $item->articuloMarca->preciosVenta->sortByDesc('fechaActualizacion')->first();
                        $precio = $precioActual ? ($precioActual->tieneDescuento && $precioActual->precioDescuento ? $precioActual->precioDescuento : $precioActual->precioVenta) : 0;
                        $valorTotal = $item->cantidadActual * $precio;
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $item->articuloMarca->articulo->nombreArticulo }}</div>
                            <div class="flex gap-2 mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                                    {{ $item->articuloMarca->marca->nombreMarca }}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                    {{ $item->articuloMarca->articulo->categoria->nombreCatArticulo ?? 'Gral.' }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $item->almacen->nombreAlmacen }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($item->cantidadActual < 10)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200 animate-pulse">
                                    {{ $item->cantidadActual }} (Crítico)
                                </span>
                            @elseif($item->cantidadActual < 20)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    {{ $item->cantidadActual }} (Bajo)
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                    {{ $item->cantidadActual }} u.
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            @if($precio > 0)
                                <div class="text-sm text-gray-900">$ {{ number_format($precio, 2) }}</div>
                                @if($precioActual->tieneDescuento && $precioActual->precioDescuento)
                                    <span class="text-xs text-green-600 font-medium">Oferta</span>
                                @endif
                            @else
                                <span class="text-xs text-red-400 italic">Sin precio</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-bold text-gray-900">$ {{ number_format($valorTotal, 2) }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <p>No se encontraron productos con los filtros seleccionados.</p>
        </div>
        @endif
    </div>

    <div>
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Navegar por Almacén</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($almacenes as $almacen)
            <a href="{{ route('stock.almacen', $almacen->idAlmacen) }}" class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:border-blue-400 hover:shadow-md transition group text-center">
                <div class="inline-flex p-2 bg-gray-50 rounded-full text-gray-400 group-hover:text-blue-500 group-hover:bg-blue-50 transition mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h4 class="font-medium text-gray-900 group-hover:text-blue-600">{{ $almacen->nombreAlmacen }}</h4>
            </a>
            @endforeach
        </div>
    </div>

</div>
@endsection