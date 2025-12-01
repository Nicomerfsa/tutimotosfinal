@extends('layout.base')

@section('title', 'Ver Proveedor')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-gray-900">{{ $proveedor->razonSocialProveedor }}</h2>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                    PROVEEDOR
                </span>
            </div>
            <p class="text-sm text-gray-500 mt-1">ID: #{{ $proveedor->idProveedor }} • Alta: {{ \Carbon\Carbon::parse($proveedor->fechaAltaProveedor)->format('d/m/Y') }}</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('proveedores.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors font-medium text-sm">
                ← Volver
            </a>
            
            <a href="{{ route('proveedores.edit', $proveedor->idProveedor) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Editar Datos
            </a>

            <a href="{{ route('ordenes-compra.create') }}?proveedor={{ $proveedor->idProveedor }}" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nueva Orden
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Datos Fiscales</h3>
                <div>
                    <label class="block text-xs text-gray-500">Razón Social</label>
                    <p class="text-base font-bold text-gray-900">{{ $proveedor->razonSocialProveedor }}</p>
                </div>
                <div>
                    <label class="block text-xs text-gray-500">CUIT</label>
                    <p class="text-sm font-mono text-gray-700 bg-gray-50 inline-block px-2 py-1 rounded border border-gray-100">
                        {{ $proveedor->cuitProveedor }}
                    </p>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Contacto</h3>
                
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <div>
                        <label class="block text-xs text-gray-500">Email</label>
                        <p class="text-sm text-gray-900">{{ $proveedor->correoProveedor ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <div>
                        <label class="block text-xs text-gray-500">Teléfono</label>
                        <p class="text-sm text-gray-900">{{ $proveedor->telefonoProveedor ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <div>
                        <label class="block text-xs text-gray-500">Dirección</label>
                        <p class="text-sm text-gray-900">{{ $proveedor->direccionProveedor ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Información Adicional</h3>
                
                @if($proveedor->webProveedor)
                <div>
                    <label class="block text-xs text-gray-500">Sitio Web</label>
                    <a href="{{ $proveedor->webProveedor }}" target="_blank" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                        {{ $proveedor->webProveedor }}
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                </div>
                @endif

                <div>
                    <label class="block text-xs text-gray-500">Observaciones</label>
                    <p class="text-sm text-gray-600 italic bg-gray-50 p-2 rounded border border-gray-100">
                        {{ $proveedor->observaciones ?: 'Sin observaciones registradas.' }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Historial de Compras</h3>
            <span class="text-xs text-gray-500">{{ $proveedor->ordenesCompra->count() }} Órdenes</span>
        </div>

        @if($proveedor->ordenesCompra->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comprobante</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($proveedor->ordenesCompra as $orden)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-900">
                            {{ $orden->comprobanteOC }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($orden->fechaOC)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($orden->estado == 'PENDIENTE')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                    PENDIENTE
                                </span>
                            @elseif($orden->estado == 'RECIBIDA')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    RECIBIDA
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    CANCELADA
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('ordenes-compra.show', $orden->idOrdenCompra) }}" class="text-blue-600 hover:text-blue-900 hover:underline">
                                Ver Orden
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Sin historial de compras</h3>
            <p class="mt-1 text-sm text-gray-500">Aún no se han generado órdenes de compra para este proveedor.</p>
            <div class="mt-6">
                <a href="{{ route('ordenes-compra.create') }}?proveedor={{ $proveedor->idProveedor }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Crear Orden de Compra
                </a>
            </div>
        </div>
        @endif
    </div>

</div>
@endsection