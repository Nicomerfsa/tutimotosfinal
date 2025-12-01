@extends('layout.base')

@section('title', 'Reporte de Ventas')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Reporte de Ventas</h2>
            <p class="text-sm text-gray-500 mt-1">Resumen de facturación y rendimiento financiero.</p>
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

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-8">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Rango de Fechas</h3>
        <form method="GET" action="{{ route('reportes.ventas') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio->format('Y-m-d') }}" 
                    class="w-full rounded-lg border-gray-300 focus:ring-gray-900 focus:border-gray-900 text-sm">
            </div>
            <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                <input type="date" name="fecha_hasta" value="{{ $fechaFin->format('Y-m-d') }}" 
                    class="w-full rounded-lg border-gray-300 focus:ring-gray-900 focus:border-gray-900 text-sm">
            </div>
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="w-full bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition text-sm font-medium shadow-sm">
                    Generar Reporte
                </button>
                <a href="{{ route('reportes.ventas') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm font-medium text-center">
                    Hoy
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Facturado</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">$ {{ number_format($totalVentas, 2) }}</p>
                </div>
                <div class="p-3 bg-green-50 rounded-full text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Impuestos (IVA)</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">$ {{ number_format($totalIva, 2) }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Descuentos</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1">$ {{ number_format($totalDescuentos, 2) }}</p>
                </div>
                <div class="p-3 bg-amber-50 rounded-full text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Operaciones</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $ventas->count() }}</p>
                </div>
                <div class="p-3 bg-gray-100 rounded-full text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Detalle del Período</h3>
            <span class="text-xs text-gray-500">
                {{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }}
            </span>
        </div>

        @if($ventas->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Factura</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">IVA</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Desc.</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($ventas as $venta)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                            <a href="{{ route('facturas.show', $venta->idFactura) }}" class="hover:underline">
                                {{ $venta->numeroFactura }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($venta->fechaFactura)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $venta->razonSocial }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                            $ {{ number_format($venta->subtotal, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                            $ {{ number_format($venta->iva, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-red-500">
                            {{ $venta->descuentoEfectivo > 0 ? '- $ ' . number_format($venta->descuentoEfectivo, 2) : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                            $ {{ number_format($venta->total, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 font-bold text-gray-900">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-sm uppercase tracking-wider">Totales del Período</td>
                        <td class="px-6 py-4 text-right text-sm">$ {{ number_format($ventas->sum('subtotal'), 2) }}</td>
                        <td class="px-6 py-4 text-right text-sm">$ {{ number_format($totalIva, 2) }}</td>
                        <td class="px-6 py-4 text-right text-sm text-red-600">$ {{ number_format($totalDescuentos, 2) }}</td>
                        <td class="px-6 py-4 text-right text-base bg-gray-100 border-t border-gray-200">$ {{ number_format($totalVentas, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="p-12 text-center text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p>No hay ventas registradas en el rango de fechas seleccionado.</p>
        </div>
        @endif
    </div>

</div>
@endsection