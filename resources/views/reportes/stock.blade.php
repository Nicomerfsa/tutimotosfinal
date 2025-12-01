@extends('layout.base')

@section('title', 'Reporte de Stock')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Reporte de Stock Actual</h2>
            <p class="text-sm text-gray-500 mt-1">Estado general del inventario y alertas de reposición.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('reportes.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a Reportes
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Imprimir Reporte
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Productos Únicos</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stock->count() }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Unidades Totales</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stockTotal, 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-purple-50 rounded-full text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm {{ $productosConStockBajo > 0 ? 'ring-2 ring-red-500 ring-opacity-50' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Alertas Stock Bajo</p>
                    <p class="text-3xl font-bold {{ $productosConStockBajo > 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $productosConStockBajo }}
                    </p>
                </div>
                <div class="p-3 {{ $productosConStockBajo > 0 ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }} rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    @if($stockBajo->count() > 0)
    <div class="mb-8">
        <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 mb-4 flex items-start justify-between">
            <div>
                <h3 class="text-lg font-bold text-red-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Atención: Productos con Stock Crítico
                </h3>
                <p class="text-sm text-red-700 mt-1">Se recomienda generar órdenes de compra para estos ítems.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-red-100">
                    <thead class="bg-red-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Marca</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Almacén</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-red-800 uppercase tracking-wider">Stock Actual</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-red-800 uppercase tracking-wider">Última Act.</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-red-100">
                        @foreach($stockBajo as $item)
                        <tr>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $item->nombreArticulo }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ $item->nombreMarca }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ $item->nombreAlmacen }}</td>
                            <td class="px-6 py-3 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200 animate-pulse">
                                    {{ $item->cantidadActual }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-sm text-right text-gray-500">
                                {{ \Carbon\Carbon::parse($item->ultimaActualizacion)->format('d/m/Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Inventario Completo</h3>
            <span class="text-xs text-gray-500">Ordenado por producto</span>
        </div>

        @if($stock->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marca</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Almacén</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Disponibilidad</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Última Act.</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-gray-50/50">
                    @foreach($stock as $item)
                    <tr class="hover:bg-white transition-colors">
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">
                            {{ $item->nombreArticulo }}
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $item->nombreMarca }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600">
                            {{ $item->nombreAlmacen }}
                        </td>
                        <td class="px-6 py-3 text-center">
                            @if($item->cantidadActual < 10)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                    {{ $item->cantidadActual }} (Crítico)
                                </span>
                            @elseif($item->cantidadActual < 20)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    {{ $item->cantidadActual }} (Bajo)
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                    {{ $item->cantidadActual }} (Normal)
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm text-right text-gray-500">
                            {{ \Carbon\Carbon::parse($item->ultimaActualizacion)->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            <p>El inventario está vacío.</p>
        </div>
        @endif
    </div>

</div>
@endsection