@extends('layout.base')

@section('title', 'Reporte de Movimientos')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Reporte de Movimientos</h2>
            <p class="text-sm text-gray-500 mt-1">Análisis detallado de flujo de stock por período.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('reportes.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a Reportes
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Imprimir
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sticky top-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Filtrar Período</h3>
                <form method="GET" action="{{ route('reportes.movimientos') }}" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                        <input type="date" name="fecha_inicio" value="{{ $fechaInicio->format('Y-m-d') }}" 
                            class="w-full rounded-lg border-gray-300 focus:ring-gray-900 focus:border-gray-900 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                        <input type="date" name="fecha_hasta" value="{{ $fechaFin->format('Y-m-d') }}" 
                            class="w-full rounded-lg border-gray-300 focus:ring-gray-900 focus:border-gray-900 text-sm">
                    </div>
                    <div class="pt-2 flex flex-col gap-2">
                        <button type="submit" class="w-full bg-gray-900 text-white py-2 rounded-lg hover:bg-gray-800 transition text-sm font-medium">
                            Aplicar Filtros
                        </button>
                        <a href="{{ route('reportes.movimientos') }}" class="w-full text-center py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition text-sm">
                            Restablecer
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-3 space-y-6">
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                    <div class="text-gray-500 text-xs font-medium uppercase">Total Operaciones</div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-gray-900">{{ $movimientos->count() }}</span>
                        <span class="text-sm text-gray-500">regs.</span>
                    </div>
                </div>
                <div class="bg-green-50 p-4 rounded-xl border border-green-100 shadow-sm">
                    <div class="text-green-600 text-xs font-bold uppercase flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        Entradas
                    </div>
                    <div class="mt-2 text-2xl font-bold text-green-800">{{ $entradas }}</div>
                </div>
                <div class="bg-red-50 p-4 rounded-xl border border-red-100 shadow-sm">
                    <div class="text-red-600 text-xs font-bold uppercase flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        Salidas
                    </div>
                    <div class="mt-2 text-2xl font-bold text-red-800">{{ $salidas }}</div>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-sm font-bold text-gray-900">Detalle del Período ({{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }})</h3>

                @if($movimientos->count() > 0)
                    @foreach($movimientos as $movimiento)
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900">Movimiento #{{ $movimiento->idMovimiento }}</span>
                                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($movimiento->fechaMovimiento)->format('d/m/Y H:i') }}</span>
                                </div>
                                
                                @if($movimiento->tipoMovimiento == 'ENTRADA')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">ENTRADA</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">SALIDA</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-6 text-sm text-gray-600">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ $movimiento->nombreAlmacen }}
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $movimiento->usuario }}
                                </div>
                            </div>
                        </div>

                        @if($movimiento->detalles->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                        <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marca</th>
                                        <th class="px-6 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Cant.</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-50">
                                    @foreach($movimiento->detalles as $detalle)
                                    <tr>
                                        <td class="px-6 py-2 text-sm text-gray-900">{{ $detalle->nombreArticulo }}</td>
                                        <td class="px-6 py-2 text-sm text-gray-500">{{ $detalle->nombreMarca }}</td>
                                        <td class="px-6 py-2 text-sm font-bold text-right {{ $movimiento->tipoMovimiento == 'ENTRADA' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $movimiento->tipoMovimiento == 'ENTRADA' ? '+' : '-' }}{{ $detalle->cantidad }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        @if($movimiento->observaciones)
                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 text-xs text-gray-500 italic">
                            <strong>Nota:</strong> {{ $movimiento->observaciones }}
                        </div>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="bg-white p-12 rounded-xl border border-gray-200 text-center shadow-sm">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900">Sin actividad</h3>
                        <p class="text-sm text-gray-500 mt-1">No se encontraron movimientos en el rango de fechas seleccionado.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection