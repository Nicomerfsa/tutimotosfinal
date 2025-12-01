@extends('layout.base')

@section('title', 'Editar Producto')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Editar Producto</h2>
            <p class="text-sm text-gray-500 mt-1">Editando: <span class="font-medium text-gray-900">{{ $producto->nombreArticulo }}</span></p>
        </div>
        <a href="{{ route('productos.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-8">
            <form method="POST" action="{{ route('productos.update', $producto->idArticulo) }}">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Producto</label>
                        <input type="text" name="nombreArticulo" value="{{ old('nombreArticulo', $producto->nombreArticulo) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 shadow-sm transition-colors">
                        @error('nombreArticulo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                            <select name="idCatArticulo" required
                                class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 shadow-sm transition-colors">
                                <option value="">Seleccionar categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->idCatArticulo }}" 
                                        {{ old('idCatArticulo', $producto->idCatArticulo) == $categoria->idCatArticulo ? 'selected' : '' }}>
                                        {{ $categoria->nombreCatArticulo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idCatArticulo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                            <select name="idMarca" required
                                class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 shadow-sm transition-colors">
                                <option value="">Seleccionar marca</option>
                                @foreach($marcas as $marca)
                                    <option value="{{ $marca->idMarca }}" 
                                        {{ old('idMarca', $producto->articulosMarcas->first()->idMarca ?? '') == $marca->idMarca ? 'selected' : '' }}>
                                        {{ $marca->nombreMarca }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idMarca')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="descripcionArticulo" rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 shadow-sm transition-colors">{{ old('descripcionArticulo', $producto->descripcionArticulo) }}</textarea>
                        @error('descripcionArticulo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div class="text-sm text-blue-800">
                            <p><strong>¿Necesitas cambiar el precio?</strong></p>
                            <p class="mt-1">La gestión de precios se realiza en un módulo separado para mantener el historial de cambios.</p>
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-4 border-t border-gray-100 pt-6">
                    
                    <a href="{{ route('productos.index') }}" class="w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>

                    @if($producto->articulosMarcas->first())
                    <a href="{{ route('precios.editar', $producto->articulosMarcas->first()->idArticuloMarca) }}" 
                       class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-amber-800 bg-amber-100 border border-amber-200 rounded-lg hover:bg-amber-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Gestionar Precio
                    </a>
                    @endif

                    <button type="submit" class="w-full sm:w-auto px-6 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                        Guardar Cambios
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection