@extends('layout.base')

@section('title', 'Ver Factura')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-gray-900">Factura {{ $factura->tipoFactura }} - {{ $factura->numeroFactura }}</h2>
                
                @if($factura->estado == 'PENDIENTE')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                        PENDIENTE DE PAGO
                    </span>
                @elseif($factura->estado == 'PAGADA')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                        PAGADA
                    </span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                        CANCELADA
                    </span>
                @endif
            </div>
            <p class="text-sm text-gray-500 mt-1">
                Emitida el {{ \Carbon\Carbon::parse($factura->fechaFactura)->format('d/m/Y') }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('facturas.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors font-medium text-sm">
                ← Volver
            </a>
            
            <a href="{{ route('facturas.print', $factura->idFactura) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Imprimir PDF
            </a>

            @if($factura->estado == 'PENDIENTE')
            <a href="{{ route('facturas.edit', $factura->idFactura) }}" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Editar
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Conceptos</h3>
                    <span class="text-xs text-gray-500">Movimiento Ref: #{{ $factura->movimiento->idMovimiento }}</span>
                </div>
                
                @if($factura->movimiento->detalles->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cant.</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unit.</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($factura->movimiento->detalles as $detalle)
                            @php
                                // Mantenemos tu lógica para obtener el precio actual
                                $precioVenta = app('App\Http\Controllers\CotizacionController')->getPrecioActual($detalle->idArticuloMarca);
                                $subtotal = $detalle->cantidad * $precioVenta;
                            @endphp
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $detalle->articuloMarca->articulo->nombreArticulo }}</div>
                                    <div class="text-xs text-gray-500">{{ $detalle->articuloMarca->marca->nombreMarca }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    {{ $detalle->cantidad }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                    $ {{ number_format($precioVenta, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                    $ {{ number_format($subtotal, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="bg-gray-50 px-6 py-6 border-t border-gray-200">
                    <div class="flex flex-col items-end space-y-2">
                        
                        <div class="flex justify-between w-full md:w-1/2 text-sm text-gray-600">
                            <span>Subtotal General:</span>
                            <span>$ {{ number_format($factura->subtotal + $factura->descuentoEfectivo, 2) }}</span>
                        </div>

                        @if($factura->descuentoEfectivo > 0)
                        <div class="flex justify-between w-full md:w-1/2 text-sm text-green-600 font-medium">
                            <span>Descuento Efectivo:</span>
                            <span>- $ {{ number_format($factura->descuentoEfectivo, 2) }}</span>
                        </div>
                        <div class="flex justify-between w-full md:w-1/2 text-sm text-gray-500 pt-1 border-t border-gray-200 border-dashed">
                            <span>Subtotal con Descuento:</span>
                            <span>$ {{ number_format($factura->subtotal, 2) }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between w-full md:w-1/2 text-sm text-gray-600">
                            <span>IVA (21%):</span>
                            <span>$ {{ number_format($factura->iva, 2) }}</span>
                        </div>

                        <div class="flex justify-between w-full md:w-1/2 pt-3 border-t border-gray-300">
                            <span class="text-base font-bold text-gray-900">TOTAL A PAGAR:</span>
                            <span class="text-xl font-bold text-gray-900">$ {{ number_format($factura->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                @else
                <div class="p-12 text-center text-gray-500">
                    No hay items registrados en el movimiento de esta factura.
                </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Facturado A</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-50 p-2 rounded-lg text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $factura->cliente->razonSocial }}</p>
                            <p class="text-xs text-gray-500 font-mono">CUIT: {{ $factura->cliente->cuit }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 uppercase mb-1">Dirección</p>
                        <p class="text-sm text-gray-700">{{ $factura->cliente->direccion ?? 'Sin dirección registrada' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Referencias</h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-xs text-gray-500 block">Movimiento de Stock</span>
                        <span class="text-sm font-medium text-gray-900">#{{ $factura->movimiento->idMovimiento }}</span>
                        <span class="text-xs text-gray-500 ml-1">({{ $factura->movimiento->almacen->nombreAlmacen }})</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 block">Tipo de Comprobante</span>
                        <div class="inline-flex items-center justify-center w-8 h-8 bg-gray-900 text-white font-bold rounded-lg mt-1">
                            {{ $factura->tipoFactura }}
                        </div>
                    </div>
                </div>
            </div>

            @if($factura->estado == 'PENDIENTE')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Gestión de Cobro</h3>
                
                <div class="space-y-3">
                    <form action="{{ route('facturas.estado', [$factura->idFactura, 'PAGADA']) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors" onclick="return confirm('¿Confirmar que la factura ha sido pagada?');">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Registrar Cobro (Pagada)
                        </button>
                    </form>

                    <form action="{{ route('facturas.estado', [$factura->idFactura, 'CANCELADA']) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-red-300 rounded-lg shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" onclick="return confirm('¿Estás seguro de ANULAR esta factura?');">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Anular Factura
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection