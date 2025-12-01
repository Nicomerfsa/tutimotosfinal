@extends('layout.base')

@section('title', 'Productos Más Vendidos')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Ranking de Productos</h2>
            <p class="text-sm text-gray-500 mt-1">Análisis de rotación y volumen de ventas.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('reportes.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a Reportes
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"></path></svg>
                Imprimir Ranking
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-8">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Periodo de Análisis</h3>
        <form method="GET" action="{{ route('reportes.productos-mas-vendidos') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio', $fechaInicio->format('Y-m-d')) }}" 
                    class="w-full rounded-lg border-gray-300 focus:ring-gray-900 focus:border-gray-900 text-sm">
            </div>
            <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin', $fechaFin->format('Y-m-d')) }}" 
                    class="w-full rounded-lg border-gray-300 focus:ring-gray-900 focus:border-gray-900 text-sm">
            </div>
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="w-full bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition text-sm font-medium shadow-sm">
                    Analizar
                </button>
                <a href="{{ route('reportes.productos-mas-vendidos') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm font-medium text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    @if($productos->count() > 0)
        @php
            $totalVendido = $productos->sum('total_vendido');
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-6">Top 5 - Distribución</h3>
                    
                    <div class="space-y-5">
                        @foreach($productos->take(5) as $index => $producto)
                            @php
                                $porcentaje = $totalVendido > 0 ? ($producto->total_vendido / $totalVendido) * 100 : 0;
                                // Colores para el top 3
                                $colorBarra = match($index) {
                                    0 => 'bg-yellow-400',
                                    1 => 'bg-gray-400',
                                    2 => 'bg-orange-400',
                                    default => 'bg-blue-500',
                                };
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs font-medium mb-1">
                                    <span class="text-gray-700 truncate w-3/4">{{ $index + 1 }}. {{ $producto->nombreArticulo }}</span>
                                    <span class="text-gray-900">{{ number_format($porcentaje, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2.5">
                                    <div class="{{ $colorBarra }} h-2.5 rounded-full" style="width: {{ $porcentaje }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Total Unidades</span>
                            <span class="text-xl font-bold text-gray-900">{{ number_format($totalVendido) }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Este reporte se basa en los movimientos de <strong>salida de stock</strong> registrados en el sistema.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Listado Completo</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-white">
                                <tr>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Rank</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marca</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ventas</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Cuota</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($productos as $index => $producto)
                                    @php
                                        $porcentaje = $totalVendido > 0 ? ($producto->total_vendido / $totalVendido) * 100 : 0;
                                        // Estilos de medalla para el top 3
                                        $rankStyle = match($index) {
                                            0 => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                            1 => 'bg-gray-100 text-gray-800 border-gray-300',
                                            2 => 'bg-orange-100 text-orange-800 border-orange-300',
                                            default => 'bg-white text-gray-500 border-transparent',
                                        };
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full text-xs font-bold border {{ $rankStyle }}">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $producto->nombreArticulo }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $producto->nombreMarca }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <span class="text-sm font-bold text-gray-900">{{ number_format($producto->total_vendido) }}</span>
                                            <span class="text-xs text-gray-500 ml-1">u.</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-600">
                                            {{ number_format($porcentaje, 1) }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-900 uppercase">Total General</td>
                                    <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">{{ number_format($totalVendido) }} u.</td>
                                    <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">100%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="text-center py-12 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <h3 class="mt-2 text-lg font-medium text-gray-900">Sin datos de ventas</h3>
            <p class="mt-1 text-sm text-gray-500">No se encontraron movimientos de salida en el rango de fechas seleccionado.</p>
        </div>
    @endif

</div>
@endsection