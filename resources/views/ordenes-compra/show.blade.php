@extends('layout.base')

@section('title', 'Ver Orden de Compra')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-gray-900">Orden #{{ $orden->comprobanteOC }}</h2>
                @if($orden->estado == 'PENDIENTE')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                        PENDIENTE
                    </span>
                @elseif($orden->estado == 'RECIBIDA')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                        RECIBIDA
                    </span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                        CANCELADA
                    </span>
                @endif
            </div>
            <p class="text-sm text-gray-500 mt-1">
                Creada el {{ \Carbon\Carbon::parse($orden->fechaOC)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($orden->fechaOC)->format('H:i') }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('ordenes-compra.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors font-medium text-sm">
                ← Volver
            </a>
            
            <a href="{{ route('ordenes-compra.print', $orden->idOrdenCompra) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Imprimir
            </a>

            @if($orden->estado == 'PENDIENTE')
            <a href="{{ route('ordenes-compra.edit', $orden->idOrdenCompra) }}" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition shadow-sm">
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
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Items de la Orden</h3>
                </div>
                
                @if($orden->detalles->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($orden->detalles as $detalle)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $detalle->articuloMarca->articulo->nombreArticulo }}</div>
                                    <div class="text-xs text-gray-500">{{ $detalle->articuloMarca->marca->nombreMarca }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    {{ $detalle->cantidad }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-8 text-center text-gray-500">
                    No hay productos asociados a esta orden.
                </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Información del Proveedor</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs text-gray-500 uppercase">Razón Social</label>
                        <p class="text-base font-medium text-gray-900">{{ $orden->proveedor->razonSocialProveedor }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 uppercase">CUIT</label>
                        <p class="text-sm text-gray-700">{{ $orden->proveedor->cuitProveedor }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 uppercase">Empresa Receptora</label>
                        <p class="text-sm text-gray-700">{{ $orden->empresa->razonSocial ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            @if($orden->estado == 'PENDIENTE')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Cambiar Estado</h3>
                
                <div class="space-y-3">
                    <form action="{{ route('ordenes-compra.estado', [$orden->idOrdenCompra, 'RECIBIDA']) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors" onclick="return confirm('¿Confirmas que has recibido la mercadería? Esto podría impactar el stock.');">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Marcar como Recibida
                        </button>
                    </form>

                    <form action="{{ route('ordenes-compra.estado', [$orden->idOrdenCompra, 'CANCELADA']) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-red-300 rounded-lg shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" onclick="return confirm('¿Seguro que deseas cancelar esta orden?');">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Cancelar Orden
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection