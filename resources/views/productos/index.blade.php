@extends('layout.base')

@section('title', 'Productos')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Gestión de Productos</h2>
            <p class="text-gray-500 text-sm mt-1">Administra el inventario, precios y stock.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('marcas.index') }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm">
                Marcas
            </a>
            <a href="{{ route('categorias.index') }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm">
                Categorías
            </a>
            <a href="{{ route('precios.actualizar') }}" class="px-3 py-2 text-sm font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Actualizar Precios
            </a>
            <a href="{{ route('productos.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Producto
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
        <form method="GET" action="{{ route('productos.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            
            <div class="md:col-span-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar producto</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="w-full pl-10 rounded-lg border-gray-300 focus:ring-gray-900 focus:border-gray-900" 
                        placeholder="Nombre, código...">
                </div>
            </div>

            <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Filtrar por Categoría</label>
                <select name="categoria" class="w-full rounded-lg border-gray-300 focus:ring-gray-900 focus:border-gray-900">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->idCatArticulo }}" {{ request('categoria') == $categoria->idCatArticulo ? 'selected' : '' }}>
                            {{ $categoria->nombreCatArticulo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3 flex gap-2">
                <button type="submit" class="w-full bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition font-medium">
                    Buscar
                </button>
                @if(request('search') || request('categoria'))
                    <a href="{{ route('productos.index') }}" class="w-auto bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition font-medium text-center">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($productos->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría / Marca</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Neto</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Precio + IVA</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($productos as $producto)
                    @php
                        $precioActual = null;
                        $articuloMarca = $producto->articulosMarcas->first();
                        if ($articuloMarca) {
                            $precioActual = $articuloMarca->preciosVenta->sortByDesc('fechaActualizacion')->first();
                        }
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors group">
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $producto->nombreArticulo }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">ID: #{{ $producto->idArticulo }}</div>
                                    @if($producto->descripcionArticulo)
                                        <div class="text-xs text-gray-500 mt-1 max-w-xs truncate">{{ $producto->descripcionArticulo }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col items-start gap-2">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $producto->categoria->nombreCatArticulo ?? 'Sin categoría' }}
                                </span>
                                @foreach($producto->articulosMarcas as $articuloMarca)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        {{ $articuloMarca->marca->nombreMarca }}
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            @if($precioActual)
                                <div class="text-sm font-bold text-gray-900">
                                    $ {{ number_format($precioActual->precioVenta, 2) }}
                                </div>
                                @if($precioActual->tieneDescuento && $precioActual->precioDescuento)
                                    <div class="text-xs font-medium text-green-600 mt-1 bg-green-50 px-2 py-0.5 rounded-md inline-block">
                                        Desc: $ {{ number_format($precioActual->precioDescuento, 2) }}
                                    </div>
                                @endif
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    No definido
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right">
                             @if($precioActual)
                                <div class="text-sm text-gray-600">
                                    @if($precioActual->tieneDescuento && $precioActual->precioDescuento)
                                        $ {{ number_format($precioActual->precioDescuento * 1.21, 2) }}
                                    @else
                                        $ {{ number_format($precioActual->precioVenta * 1.21, 2) }}
                                    @endif
                                </div>
                                <div class="text-xs text-gray-400">IVA incl.</div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('productos.show', $producto->idArticulo) }}" class="text-gray-400 hover:text-blue-600 transition-colors" title="Ver detalles">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('productos.edit', $producto->idArticulo) }}" class="text-gray-400 hover:text-amber-600 transition-colors" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('productos.destroy', $producto->idArticulo) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" onclick="return confirm('¿Estás seguro de eliminar el producto {{ $producto->nombreArticulo }}?')" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
             <span class="text-xs text-gray-500">Mostrando {{ $productos->count() }} resultados</span>
        </div>

        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No se encontraron productos</h3>
                <p class="mt-1 text-sm text-gray-500">Intenta con otros filtros o agrega un nuevo producto.</p>
                <div class="mt-6">
                    <a href="{{ route('productos.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Agregar Producto
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection