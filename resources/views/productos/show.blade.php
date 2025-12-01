@extends('layout.base')

@section('title', 'Detalles del Producto')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Detalles del Producto</h2>
        <a href="{{ route('productos.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
    </div>

    @php
        // Extraemos lógica compleja a variables simples al inicio
        $marcaRelacion = $producto->articulosMarcas->first();
        $precioActual = $marcaRelacion ? $marcaRelacion->preciosVenta->sortByDesc('fechaActualizacion')->first() : null;
        
        // Convertir fechaActualizacion a Carbon si existe
        $fechaActualizacion = $precioActual && $precioActual->fechaActualizacion ? 
            \Carbon\Carbon::parse($precioActual->fechaActualizacion) : null;
    @endphp

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <div class="p-8 space-y-8">
            
            <div>
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $producto->nombreArticulo }}</h1>
                        <p class="text-sm text-gray-400 mt-1">ID Producto: #{{ $producto->idArticulo }}</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $producto->categoria->nombreCatArticulo ?? 'Sin categoría' }}
                        </span>
                        @if($marcaRelacion)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                {{ $marcaRelacion->marca->nombreMarca }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                                Sin Marca
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Precio Actual de Venta</h3>
                    
                    @if($precioActual)
                        <div class="flex items-baseline gap-3">
                            @if($precioActual->tieneDescuento && $precioActual->precioDescuento)
                                <span class="text-3xl font-bold text-green-600">
                                    $ {{ number_format($precioActual->precioDescuento, 2) }}
                                </span>
                                <span class="text-lg text-gray-400 line-through decoration-gray-400">
                                    $ {{ number_format($precioActual->precioVenta, 2) }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Oferta Activa
                                </span>
                            @else
                                <span class="text-3xl font-bold text-gray-900">
                                    $ {{ number_format($precioActual->precioVenta, 2) }}
                                </span>
                            @endif
                        </div>
                        @if($fechaActualizacion)
                            <p class="text-xs text-gray-400 mt-2">
                                Actualizado el {{ $fechaActualizacion->format('d/m/Y') }} a las {{ $fechaActualizacion->format('H:i') }}
                            </p>
                        @else
                            <p class="text-xs text-gray-400 mt-2">
                                Fecha de actualización no disponible
                            </p>
                        @endif
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            Precio No Definido
                        </span>
                        <p class="text-xs text-gray-500 mt-1">Este producto no aparecerá en ventas hasta tener precio.</p>
                    @endif
                </div>

                @if($marcaRelacion)
                    <a href="{{ route('precios.editar', $marcaRelacion->idArticuloMarca) }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-amber-600 transition shadow-sm">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Gestionar Precio
                    </a>
                @endif
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-900 mb-2">Descripción del Producto</h3>
                <div class="prose prose-sm text-gray-600 bg-white">
                    @if($producto->descripcionArticulo)
                        <p>{{ $producto->descripcionArticulo }}</p>
                    @else
                        <p class="italic text-gray-400">Sin descripción disponible.</p>
                    @endif
                </div>
            </div>

        </div>

        <div class="bg-gray-50 px-8 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
            <span class="text-xs text-gray-500">
                Creado: {{ $producto->created_at ? $producto->created_at->format('d/m/Y') : 'N/A' }}
            </span>
            
            <div class="flex gap-3">
                <form action="{{ route('productos.destroy', $producto->idArticulo) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto permanentemente?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 bg-white border border-gray-300 rounded-lg hover:bg-red-50 hover:border-red-200 transition">
                        Eliminar
                    </button>
                </form>

                <a href="{{ route('productos.edit', $producto->idArticulo) }}" class="flex items-center gap-2 px-6 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar Información
                </a>
            </div>
        </div>

    </div>
</div>
@endsection