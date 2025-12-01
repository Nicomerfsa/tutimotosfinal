@extends('layout.base')

@section('title', 'Alertas de Stock Bajo')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2 bg-red-100 rounded-lg text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Alertas de Stock Bajo</h2>
            </div>
            <p class="text-sm text-gray-500 mt-1 ml-1">Productos que han alcanzado o están por debajo del nivel mínimo.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('stock.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Stock General
            </a>
            <a href="{{ route('movimientos.entrada') }}" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Reponer Stock (Entrada)
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        @if($stockBajo->count() > 0)
        
        <div class="bg-red-50 border-b border-red-100 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2 text-red-800 font-medium">
                <span>⚠️ Se encontraron <strong>{{ $stockBajo->count() }}</strong> productos en estado crítico.</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación (Almacén)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Actual</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Última Act.</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($stockBajo as $item)
                    <tr class="hover:bg-red-50 transition-colors group">
                        
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $item->articuloMarca->articulo->nombreArticulo }}</div>
                            <div class="text-xs text-gray-500">{{ $item->articuloMarca->marca->nombreMarca }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $item->almacen->nombreAlmacen }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $item->articuloMarca->articulo->categoria->nombreCatArticulo ?? '-' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-700 border border-red-200 animate-pulse">
                                {{ $item->cantidadActual }} u.
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($item->ultimaActualizacion)->format('d/m/Y') }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('movimientos.entrada') }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                Reponer
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @else
            <div class="text-center py-16 bg-white">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="mt-2 text-xl font-bold text-gray-900">¡Todo en orden!</h3>
                <p class="mt-2 text-sm text-gray-500">No hay productos con stock bajo en este momento.</p>
                <div class="mt-6">
                    <a href="{{ route('stock.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Ver Inventario Completo
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection