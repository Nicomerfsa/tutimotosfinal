@extends('layout.base')

@section('title', 'Panel de Reportes')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Sistema de Reportes</h2>
            <p class="text-sm text-gray-500 mt-1">Métricas, estadísticas y análisis del negocio.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver al Dashboard
        </a>
    </div>

    <div class="mb-10">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Métricas en Tiempo Real</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col">
                <span class="text-xs font-medium text-gray-500 uppercase mb-1">Productos</span>
                <div class="flex items-center justify-between mt-auto">
                    <span class="text-2xl font-bold text-gray-900">{{ \App\Models\Articulo::count() }}</span>
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col">
                <span class="text-xs font-medium text-gray-500 uppercase mb-1">Clientes</span>
                <div class="flex items-center justify-between mt-auto">
                    <span class="text-2xl font-bold text-gray-900">{{ \App\Models\Cliente::where('estado', 'ACTIVO')->count() }}</span>
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col">
                <span class="text-xs font-medium text-gray-500 uppercase mb-1">Proveedores</span>
                <div class="flex items-center justify-between mt-auto">
                    <span class="text-2xl font-bold text-gray-900">{{ \App\Models\Proveedor::count() }}</span>
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col">
                <span class="text-xs font-medium text-gray-500 uppercase mb-1">Unidades Stock</span>
                <div class="flex items-center justify-between mt-auto">
                    <span class="text-2xl font-bold text-gray-900">{{ number_format(\App\Models\StockPorAlmacen::sum('cantidadActual'), 0, ',', '.') }}</span>
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col col-span-2 md:col-span-1">
                <span class="text-xs font-medium text-gray-500 uppercase mb-1">Ventas (Mes Actual)</span>
                <div class="flex items-center justify-between mt-auto">
                    <span class="text-xl font-bold text-green-600 truncate">$ {{ number_format(\App\Models\Factura::where('estado', 'PAGADA')->whereMonth('fechaFactura', now()->month)->sum('total'), 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div>
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Reportes Detallados</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <a href="{{ route('reportes.ventas') }}" class="group bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-300 transition-all duration-200 flex items-start gap-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Reporte de Ventas</h4>
                    <p class="text-sm text-gray-500 mt-1">Analiza el rendimiento financiero, facturación, impuestos y descuentos aplicados en un período.</p>
                </div>
            </a>

            <a href="{{ route('reportes.stock') }}" class="group bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-purple-300 transition-all duration-200 flex items-start gap-4">
                <div class="p-3 bg-purple-50 text-purple-600 rounded-lg group-hover:bg-purple-600 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors">Estado de Inventario</h4>
                    <p class="text-sm text-gray-500 mt-1">Consulta el stock actual, valorización de inventario y alertas de reposición crítica.</p>
                </div>
            </a>

            <a href="{{ route('reportes.movimientos') }}" class="group bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-orange-300 transition-all duration-200 flex items-start gap-4">
                <div class="p-3 bg-orange-50 text-orange-600 rounded-lg group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-orange-600 transition-colors">Flujo de Movimientos</h4>
                    <p class="text-sm text-gray-500 mt-1">Auditoría completa de entradas y salidas de mercadería por fecha y responsable.</p>
                </div>
            </a>

            <a href="{{ route('reportes.productos-mas-vendidos') }}" class="group bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-yellow-300 transition-all duration-200 flex items-start gap-4">
                <div class="p-3 bg-yellow-50 text-yellow-600 rounded-lg group-hover:bg-yellow-500 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-yellow-600 transition-colors">Ranking de Productos</h4>
                    <p class="text-sm text-gray-500 mt-1">Descubre cuáles son los artículos con mayor rotación y volumen de ventas.</p>
                </div>
            </a>

        </div>
    </div>

</div>
@endsection