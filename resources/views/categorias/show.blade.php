@extends('layout.base')

@section('title', 'Ver Categoría')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Detalles de Categoría</h2>
            <p class="text-sm text-gray-500 mt-1">ID Categoría: #{{ $categoria->idCatArticulo }}</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('categorias.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors font-medium text-sm">
                ← Volver
            </a>
            
            <a href="{{ route('categorias.edit', $categoria->idCatArticulo) }}" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Editar Categoría
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $categoria->nombreCatArticulo }}</h1>
        
        <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Descripción</h3>
            <p class="text-gray-700">
                {{ $categoria->descripcionCatArticulo ?: 'Sin descripción disponible.' }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Productos en esta Categoría</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ $categoria->articulos->count() }} Productos
            </span>
        </div>

        @if($categoria->articulos->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marca(s)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción Breve</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($categoria->articulos as $articulo)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $articulo->nombreArticulo }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($articulo->articulosMarcas as $articuloMarca)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                                        {{ $articuloMarca->marca->nombreMarca }}
                                    </span>
                                @empty
                                    <span class="text-xs text-gray-400">-</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500 max-w-xs truncate">
                                {{ $articulo->descripcionArticulo ?: '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('productos.show', $articulo->idArticulo) }}" class="text-blue-600 hover:text-blue-900 hover:underline">
                                Ver
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
            <h3 class="mt-2 text-sm font-medium text-gray-900">Categoría Vacía</h3>
            <p class="mt-1 text-sm text-gray-500">No hay productos asociados a esta categoría actualmente.</p>
            <div class="mt-6">
                <a href="{{ route('productos.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Agregar Producto
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection