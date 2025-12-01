@extends('layout.base')

@section('title', 'Dashboard Principal')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Resumen General</h2>
            <p class="text-sm text-gray-500 mt-1">Bienvenido al panel de control de Tuti Motos.</p>
        </div>
        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
            {{ now()->format('d/m/Y') }}
        </span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center group hover:border-blue-300 transition">
            <div class="p-2 bg-blue-50 rounded-full text-blue-600 mb-2 group-hover:bg-blue-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <span class="text-2xl font-bold text-gray-900">{{ $stats['total_productos'] }}</span>
            <span class="text-xs text-gray-500 uppercase font-medium">Productos</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center group hover:border-purple-300 transition">
            <div class="p-2 bg-purple-50 rounded-full text-purple-600 mb-2 group-hover:bg-purple-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <span class="text-2xl font-bold text-gray-900">{{ $stats['total_clientes'] }}</span>
            <span class="text-xs text-gray-500 uppercase font-medium">Clientes</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center group hover:border-indigo-300 transition">
            <div class="p-2 bg-indigo-50 rounded-full text-indigo-600 mb-2 group-hover:bg-indigo-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <span class="text-2xl font-bold text-gray-900">{{ $stats['total_proveedores'] }}</span>
            <span class="text-xs text-gray-500 uppercase font-medium">Proveedores</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center group hover:border-green-300 transition">
            <div class="p-2 bg-green-50 rounded-full text-green-600 mb-2 group-hover:bg-green-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <span class="text-2xl font-bold text-gray-900">{{ number_format($stats['stock_total'], 0, ',', '.') }}</span>
            <span class="text-xs text-gray-500 uppercase font-medium">Unidades</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center group hover:border-emerald-300 transition">
            <div class="p-2 bg-emerald-50 rounded-full text-emerald-600 mb-2 group-hover:bg-emerald-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-lg font-bold text-gray-900">$ {{ number_format($stats['ventas_mes'], 0, ',', '.') }}</span>
            <span class="text-xs text-gray-500 uppercase font-medium">Ventas (Mes)</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center group hover:border-amber-300 transition">
            <div class="p-2 bg-amber-50 rounded-full text-amber-600 mb-2 group-hover:bg-amber-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-2xl font-bold {{ $stats['ordenes_pendientes'] > 0 ? 'text-amber-600' : 'text-gray-900' }}">{{ $stats['ordenes_pendientes'] }}</span>
            <span class="text-xs text-gray-500 uppercase font-medium">Pendientes</span>
        </div>
    </div>

    <div class="mb-8">
        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Accesos Rápidos</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('movimientos.entrada') }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg hover:border-green-400 hover:shadow-sm transition group">
                <span class="bg-green-100 text-green-700 p-1 rounded group-hover:bg-green-600 group-hover:text-white transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></span>
                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Entrada Stock</span>
            </a>
            <a href="{{ route('movimientos.salida') }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg hover:border-red-400 hover:shadow-sm transition group">
                <span class="bg-red-100 text-red-700 p-1 rounded group-hover:bg-red-600 group-hover:text-white transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg></span>
                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Salida Stock</span>
            </a>
            <a href="{{ route('cotizaciones.create') }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg hover:border-blue-400 hover:shadow-sm transition group">
                <span class="bg-blue-100 text-blue-700 p-1 rounded group-hover:bg-blue-600 group-hover:text-white transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></span>
                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Cotizar</span>
            </a>
            <!-- Botón de Remitos agregado aquí -->
            <a href="{{ route('remitos.index') }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg hover:border-teal-400 hover:shadow-sm transition group">
                <span class="bg-teal-100 text-teal-700 p-1 rounded group-hover:bg-teal-600 group-hover:text-white transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></span>
                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Remitos</span>
            </a>
            <a href="{{ route('facturas.create') }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg hover:border-indigo-400 hover:shadow-sm transition group">
                <span class="bg-indigo-100 text-indigo-700 p-1 rounded group-hover:bg-indigo-600 group-hover:text-white transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 36v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></span>
                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Facturar</span>
            </a>
            <a href="{{ route('ordenes-compra.index') }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg hover:border-gray-400 hover:shadow-sm transition group">
                <span class="bg-gray-100 text-gray-700 p-1 rounded group-hover:bg-gray-600 group-hover:text-white transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></span>
                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Compra</span>
            </a>
            <!-- Botón de Reportes agregado aquí -->
            <a href="/reportes/" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg hover:border-orange-400 hover:shadow-sm transition group">
                <span class="bg-orange-100 text-orange-700 p-1 rounded group-hover:bg-orange-600 group-hover:text-white transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></span>
                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Reportes</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Últimos Movimientos</h3>
                <a href="{{ route('movimientos.index') }}" class="text-xs text-blue-600 hover:underline">Ver todos</a>
            </div>
            
            @if($movimientos_recientes->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($movimientos_recientes as $movimiento)
                <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-full {{ $movimiento->tipoMovimiento == 'ENTRADA' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                            @if($movimiento->tipoMovimiento == 'ENTRADA')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $movimiento->tipoMovimiento }}</p>
                            <p class="text-xs text-gray-500">{{ $movimiento->nombreAlmacen }} • {{ $movimiento->usuario }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">{{ $movimiento->fecha_humana }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-8 text-center text-gray-500 text-sm">No hay actividad reciente.</div>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                    </span>
                    Alertas de Stock
                </h3>
                <a href="{{ route('stock.bajo') }}" class="text-xs text-red-600 hover:underline">Gestionar</a>
            </div>

            @if($stock_bajo->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($stock_bajo as $stock)
                <div class="px-6 py-3 flex items-center justify-between hover:bg-red-50 transition group">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $stock->nombreArticulo }}</p>
                        <p class="text-xs text-gray-500">{{ $stock->nombreAlmacen }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                        {{ $stock->cantidadActual }} u.
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-8 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 mb-2">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="text-sm font-medium text-gray-900">Todo en orden</p>
                <p class="text-xs text-gray-500">No hay productos con stock crítico.</p>
            </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Productos Más Vendidos (Mes Actual)</h3>
        </div>
        
        @if($productos_mas_vendidos->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-gray-100">
            @foreach($productos_mas_vendidos->take(4) as $index => $producto)
            <div class="p-4 flex items-center gap-3 hover:bg-gray-50 transition">
                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-yellow-50 text-yellow-600 font-bold border border-yellow-100">
                    #{{ $index + 1 }}
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 truncate w-40" title="{{ $producto->nombreArticulo }}">{{ $producto->nombreArticulo }}</p>
                    <p class="text-xs text-gray-500">{{ $producto->total_vendido }} Ventas</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-6 text-center text-sm text-gray-500">Aún no hay datos de ventas suficientes este mes.</div>
        @endif
    </div>

</div>
@endsection