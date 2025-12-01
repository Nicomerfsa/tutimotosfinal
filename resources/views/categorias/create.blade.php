@extends('layout.base')

@section('title', 'Nueva Categoría')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Crear Nueva Categoría</h2>
        <a href="{{ route('categorias.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6">
            <form method="POST" action="{{ route('categorias.store') }}">
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <label for="nombreCatArticulo" class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre de la Categoría
                        </label>
                        <input 
                            type="text" 
                            name="nombreCatArticulo" 
                            id="nombreCatArticulo" 
                            value="{{ old('nombreCatArticulo') }}" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 shadow-sm transition-colors"
                            placeholder="Ej: Neumáticos, Aceites, Frenos..."
                        >
                        @error('nombreCatArticulo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="descripcionCatArticulo" class="block text-sm font-medium text-gray-700 mb-1">
                            Descripción
                        </label>
                        <textarea 
                            name="descripcionCatArticulo" 
                            id="descripcionCatArticulo" 
                            rows="4" 
                            class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 shadow-sm transition-colors"
                            placeholder="Describe brevemente esta categoría..."
                        >{{ old('descripcionCatArticulo') }}</textarea>
                        @error('descripcionCatArticulo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                    <a href="{{ route('categorias.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors shadow-sm">
                        Crear Categoría
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection