@extends('layout.base')

@section('title', 'Ver Marca')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Detalles de Marca</h2>
            <p class="text-sm text-gray-500 mt-1">ID Marca: #{{ $marca->idMarca }}</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('marcas.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors font-medium text-sm">
                ← Volver
            </a>
            
            <a href="{{ route('marcas.edit', $marca->idMarca) }}" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Editar Marca
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-3 bg-gray-100 rounded-full">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $marca->nombreMarca }}</h1>
        </div>
        
        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Descripción</h3>
            <p class="text-gray-700">
                {{ $marca->descripcionMarca ?: 'Sin descripción disponible para esta marca.' }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Productos de {{ $marca->nombreMarca }}</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                {{ $marca->articulosMarcas->count() }} Items
            </span>
        </div>

        @if($marca->articulosMarcas->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción Breve</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($marca->articulosMarcas as $articuloMarca)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $articuloMarca->articulo->nombreArticulo }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $articuloMarca->articulo->categoria->nombreCatArticulo ?? 'General' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500 max-w-xs truncate">
                                {{ $articuloMarca->articulo->descripcionArticulo ?: '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('productos.show', $articuloMarca->articulo->idArticulo) }}" class="text-blue-600 hover:text-blue-900 hover:underline">
                                Ver Producto
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Marca sin productos</h3>
            <p class="mt-1 text-sm text-gray-500">Aún no hay productos asociados a esta marca en el inventario.</p>
            <div class="mt-6">
                <a href="{{ route('productos.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Crear Producto
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection