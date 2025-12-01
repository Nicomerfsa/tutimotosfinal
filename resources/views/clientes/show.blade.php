@extends('layout.base')

@section('title', 'Ver Cliente')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-gray-900">Perfil del Cliente</h2>
                @if($cliente->estado == 'ACTIVO')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                        ACTIVO
                    </span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                        INACTIVO
                    </span>
                @endif
            </div>
            <p class="text-sm text-gray-500 mt-1">ID Cliente: #{{ $cliente->idCliente }} • Alta: {{ \Carbon\Carbon::parse($cliente->fechaAlta)->format('d/m/Y') }}</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('clientes.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors font-medium text-sm">
                ← Volver
            </a>
            
            <a href="{{ route('clientes.edit', $cliente->idCliente) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Editar Datos
            </a>

            <a href="{{ route('cotizaciones.create') }}?cliente={{ $cliente->idCliente }}" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Nueva Cotización
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="md:col-span-2 lg:col-span-1">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Razón Social</label>
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ $cliente->razonSocial }}</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">CUIT / DNI</label>
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-gray-50 rounded-lg text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.956 2.008-2.008 2.008h-.268"></path></svg>
                    </div>
                    <span class="text-base font-mono text-gray-900">{{ $cliente->cuit }}</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Email</label>
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-base text-gray-900 truncate" title="{{ $cliente->correo }}">{{ $cliente->correo ?? 'No registrado' }}</span>
                </div>
            </div>

             <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Teléfono</label>
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <span class="text-base text-gray-900">{{ $cliente->telefono ?? 'No registrado' }}</span>
                </div>
            </div>

             <div class="md:col-span-2 lg:col-span-4">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Dirección</label>
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <span class="text-base text-gray-900">{{ $cliente->direccion ?? 'No registrada' }}</span>
                </div>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Cotizaciones del Cliente</h3>
            <span class="text-xs text-gray-500">{{ $cliente->cotizaciones->count() }} registradas</span>
        </div>

        @if($cliente->cotizaciones->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($cliente->cotizaciones as $cotizacion)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            #{{ $cotizacion->numeroCotizacion }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($cotizacion->fechaCotizacion)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $cotizacion->estado == 'APROBADA' ? 'bg-green-100 text-green-800' : 
                                  ($cotizacion->estado == 'RECHAZADA' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $cotizacion->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                            $ {{ number_format($cotizacion->total, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('cotizaciones.show', $cotizacion->idCotizacion) }}" class="text-blue-600 hover:text-blue-900 hover:underline">
                                Ver Detalles
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Sin cotizaciones</h3>
            <p class="mt-1 text-sm text-gray-500">Este cliente aún no tiene historial de cotizaciones.</p>
            <div class="mt-6">
                <a href="{{ route('cotizaciones.create') }}?cliente={{ $cliente->idCliente }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Crear Cotización
                </a>
            </div>
        </div>
        @endif
    </div>

</div>
@endsection