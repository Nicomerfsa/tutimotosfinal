@extends('layout.base')

@section('title', 'Ver Movimiento')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-gray-900">Movimiento #{{ $movimiento->idMovimiento }}</h2>
                
                @if($movimiento->tipoMovimiento == 'ENTRADA')
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        ENTRADA
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        SALIDA
                    </span>
                @endif
            </div>
            <p class="text-sm text-gray-500 mt-1">
                Registrado el {{ \Carbon\Carbon::parse($movimiento->fechaMovimiento)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($movimiento->fechaMovimiento)->format('H:i') }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('movimientos.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m7 7h18"></path></svg>
                Volver
            </a>
            <button onclick="window.print()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Imprimir
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Datos del Registro</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div>
                <label class="block text-xs text-gray-500 uppercase mb-1">Almacén Afectado</label>
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-gray-100 rounded text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900">{{ $movimiento->nombreAlmacen }}</span>
                </div>
            </div>

            <div>
                <label class="block text-xs text-gray-500 uppercase mb-1">Operador / Usuario</label>
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-gray-100 rounded text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900">{{ $movimiento->usuario }}</span>
                </div>
            </div>

            @if($movimiento->idRemito)
            <div>
                <label class="block text-xs text-gray-500 uppercase mb-1">Remito Asociado</label>
                <a href="{{ route('remitos.show', $movimiento->idRemito) }}" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 transition group">
                    <div class="p-1.5 bg-blue-50 rounded text-blue-500 group-hover:bg-blue-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="text-sm font-medium underline decoration-dotted">#{{ $movimiento->numeroRemito }}</span>
                </a>
            </div>
            @else
            <div>
                <label class="block text-xs text-gray-500 uppercase mb-1">Remito</label>
                <span class="text-sm text-gray-400 italic">Sin remito asociado</span>
            </div>
            @endif

            <div class="md:col-span-3 mt-2">
                <label class="block text-xs text-gray-500 uppercase mb-1">Observaciones</label>
                <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 text-sm text-gray-700">
                    {{ $movimiento->observaciones ?? 'Sin observaciones registradas.' }}
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Items del Movimiento</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                {{ $detalles->count() }} Líneas
            </span>
        </div>

        @if($detalles->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marca</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Cantidad</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($detalles as $detalle)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-gray-900">{{ $detalle->nombreArticulo }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                                {{ $detalle->nombreMarca }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-base font-bold {{ $movimiento->tipoMovimiento == 'ENTRADA' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $movimiento->tipoMovimiento == 'ENTRADA' ? '+' : '-' }}{{ $detalle->cantidad }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <p>No hay productos asociados a este registro.</p>
        </div>
        @endif
    </div>

</div>
@endsection