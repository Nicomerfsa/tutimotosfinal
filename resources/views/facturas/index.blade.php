@extends('layout.base')

@section('title', 'Facturas')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Gestión de Facturas</h2>
            <p class="text-sm text-gray-500 mt-1">Control de comprobantes emitidos y estados de pago.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('reportes.ventas') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Reporte Ventas
            </a>
            <a href="{{ route('facturas.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nueva Factura
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
        <form method="GET" action="{{ route('facturas.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            
            <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="w-full pl-10 rounded-lg border-gray-300 focus:ring-gray-900 focus:border-gray-900 text-sm" 
                        placeholder="Número, cliente o CUIT...">
                </div>
            </div>

            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="estado" class="w-full rounded-lg border-gray-300 focus:ring-gray-900 focus:border-gray-900 text-sm">
                    <option value="">Todos</option>
                    <option value="PENDIENTE" {{ request('estado') == 'PENDIENTE' ? 'selected' : '' }}>Pendientes</option>
                    <option value="PAGADA" {{ request('estado') == 'PAGADA' ? 'selected' : '' }}>Pagadas</option>
                    <option value="CANCELADA" {{ request('estado') == 'CANCELADA' ? 'selected' : '' }}>Canceladas</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                <select name="tipo" class="w-full rounded-lg border-gray-300 focus:ring-gray-900 focus:border-gray-900 text-sm">
                    <option value="">Todos</option>
                    <option value="A" {{ request('tipo') == 'A' ? 'selected' : '' }}>Tipo A</option>
                    <option value="B" {{ request('tipo') == 'B' ? 'selected' : '' }}>Tipo B</option>
                    <option value="C" {{ request('tipo') == 'C' ? 'selected' : '' }}>Tipo C</option>
                </select>
            </div>

            <div class="md:col-span-3 flex gap-2">
                <button type="submit" class="w-full bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition text-sm font-medium">
                    Filtrar
                </button>
                @if(request('search') || request('estado') || request('tipo'))
                    <a href="{{ route('facturas.index') }}" class="w-auto bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm font-medium text-center">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($facturas->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número / Fecha</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($facturas as $factura)
                    <tr class="hover:bg-gray-50 transition-colors">
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $factura->numeroFactura }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($factura->fechaFactura)->format('d/m/Y') }}
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full text-xs font-bold border 
                                {{ $factura->tipoFactura == 'A' ? 'bg-blue-50 text-blue-700 border-blue-200' : 
                                  ($factura->tipoFactura == 'B' ? 'bg-gray-50 text-gray-700 border-gray-200' : 'bg-gray-100 text-gray-600 border-gray-300') }}">
                                {{ $factura->tipoFactura }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $factura->cliente->razonSocial }}</div>
                            <div class="text-xs text-gray-500">CUIT: {{ $factura->cliente->cuit }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-bold text-gray-900">$ {{ number_format($factura->total, 2) }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($factura->estado == 'PENDIENTE')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    PENDIENTE
                                </span>
                            @elseif($factura->estado == 'PAGADA')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                    PAGADA
                                </span>
                            @else
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                    CANCELADA
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-3">
                                
                                {{-- Ver --}}
                                <a href="{{ route('facturas.show', $factura->idFactura) }}" class="text-gray-400 hover:text-blue-600 transition-colors" title="Ver detalle">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>

                                {{-- Imprimir --}}
                                <a href="{{ route('facturas.print', $factura->idFactura) }}" target="_blank" class="text-gray-400 hover:text-gray-800 transition-colors" title="Imprimir PDF">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </a>

                                {{-- Editar (Solo PENDIENTE) --}}
                                @if($factura->estado == 'PENDIENTE')
                                    <a href="{{ route('facturas.edit', $factura->idFactura) }}" class="text-gray-400 hover:text-amber-600 transition-colors" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                @endif

                                {{-- Eliminar --}}
                                <form action="{{ route('facturas.destroy', $factura->idFactura) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" onclick="return confirm('¿Estás seguro de eliminar esta factura?')" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $facturas->links() }}
        </div>

        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No hay facturas</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza generando facturas desde tus movimientos.</p>
                <div class="mt-6">
                    <a href="{{ route('facturas.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Nueva Factura
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection