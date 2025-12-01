@extends('layout.base')

@section('title', 'Editar Marca')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Editar Marca</h2>
            <p class="text-sm text-gray-500 mt-1">Editando: <span class="font-medium text-gray-900">{{ $marca->nombreMarca }}</span></p>
        </div>
        <a href="{{ route('marcas.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-8">
            <form method="POST" action="{{ route('marcas.update', $marca->idMarca) }}">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Marca</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            </div>
                            <input type="text" name="nombreMarca" value="{{ old('nombreMarca', $marca->nombreMarca) }}" required
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 shadow-sm transition-colors">
                        </div>
                        @error('nombreMarca')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="descripcionMarca" rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 shadow-sm transition-colors"
                            placeholder="Información adicional...">{{ old('descripcionMarca', $marca->descripcionMarca) }}</textarea>
                        @error('descripcionMarca')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="mt-8 flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                    <a href="{{ route('marcas.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                        Guardar Cambios
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection