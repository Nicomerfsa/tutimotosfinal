@extends('layout.base')

@section('title', 'Ver Cotización')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-gray-900">Cotización #{{ $cotizacion->numeroCotizacion }}</h2>
                @if($cotizacion->estado == 'PENDIENTE')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                        PENDIENTE
                    </span>
                @elseif($cotizacion->estado == 'APROBADA')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                        APROBADA
                    </span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                        RECHAZADA
                    </span>
                @endif
            </div>
            <p class="text-sm text-gray-500 mt-1">
                Generada el {{ \Carbon\Carbon::parse($cotizacion->fechaCotizacion)->format('d/m/Y') }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('cotizaciones.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors font-medium text-sm">
                ← Volver
            </a>
            
            <a href="{{ route('cotizaciones.print', $cotizacion->idCotizacion) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Imprimir PDF
            </a>

            @if($cotizacion->estado == 'PENDIENTE')
            <a href="{{ route('cotizaciones.edit', $cotizacion->idCotizacion) }}" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Editar
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Items Cotizados</h3>
                </div>
                
                @if($cotizacion->detalles->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cant.</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unitario</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($cotizacion->detalles as $detalle)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $detalle->articuloMarca->articulo->nombreArticulo }}</div>
                                    <div class="text-xs text-gray-500">{{ $detalle->articuloMarca->marca->nombreMarca }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    {{ $detalle->cantidad }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                    $ {{ number_format($detalle->precioUnitario, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                    $ {{ number_format($detalle->cantidad * $detalle->precioUnitario, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Subtotal Neto:</td>
                                <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">$ {{ number_format($cotizacion->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">IVA (21%):</td>
                                <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">$ {{ number_format($cotizacion->iva, 2) }}</td>
                            </tr>
                            <tr class="bg-gray-100 border-t border-gray-200">
                                <td colspan="3" class="px-6 py-4 text-right text-base font-bold text-gray-900">TOTAL FINAL:</td>
                                <td class="px-6 py-4 text-right text-xl font-bold text-gray-900">$ {{ number_format($cotizacion->total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="p-8 text-center text-gray-500">
                    No hay productos cargados en esta cotización.
                </div>
                @endif
            </div>

            @if($cotizacion->observaciones)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-sm font-bold text-gray-900 mb-2">Observaciones</h4>
                <p class="text-sm text-gray-600">{{ $cotizacion->observaciones }}</p>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Cliente</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs text-gray-500 uppercase">Razón Social</label>
                        <a href="{{ route('clientes.show', $cotizacion->cliente->idCliente) }}" class="text-base font-medium text-blue-600 hover:underline">
                            {{ $cotizacion->cliente->razonSocial }}
                        </a>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 uppercase">CUIT</label>
                        <p class="text-sm text-gray-700 font-mono">{{ $cotizacion->cliente->cuit }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 uppercase">Contacto</label>
                        <p class="text-sm text-gray-700">{{ $cotizacion->cliente->correo ?? '-' }}</p>
                        <p class="text-sm text-gray-700">{{ $cotizacion->cliente->telefono ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Condiciones</h3>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Validez de oferta:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $cotizacion->validezDias }} días</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Vence el:</span>
                    <span class="text-sm font-medium {{ \Carbon\Carbon::parse($cotizacion->fechaCotizacion)->addDays($cotizacion->validezDias)->isPast() ? 'text-red-600' : 'text-green-600' }}">
                        {{ \Carbon\Carbon::parse($cotizacion->fechaCotizacion)->addDays($cotizacion->validezDias)->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            @if($cotizacion->estado == 'PENDIENTE')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Resolución</h3>
                
                <div class="space-y-3">
                    <form action="{{ route('cotizaciones.estado', [$cotizacion->idCotizacion, 'APROBADA']) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors" onclick="return confirm('¿Aprobar cotización? Esto podría generar una orden de venta.');">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Aprobar
                        </button>
                    </form>

                    <form action="{{ route('cotizaciones.estado', [$cotizacion->idCotizacion, 'RECHAZADA']) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-red-300 rounded-lg shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" onclick="return confirm('¿Rechazar esta cotización?');">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Rechazar
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection