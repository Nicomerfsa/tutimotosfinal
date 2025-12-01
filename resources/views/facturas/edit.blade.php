@extends('layout.base')

@section('title', 'Editar Factura')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Editar Factura #{{ $factura->numeroFactura }}</h2>
            <p class="text-sm text-gray-500 mt-1">Modifica los datos fiscales o el descuento.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('facturas.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
            <p class="font-bold text-red-700">Corrige los siguientes errores:</p>
            <ul class="list-disc list-inside text-sm text-red-600 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('facturas.update', $factura->idFactura) }}" id="facturaForm">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Datos Editables</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                            <select name="idCliente" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                <option value="">Seleccionar cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->idCliente }}" {{ $factura->idCliente == $cliente->idCliente ? 'selected' : '' }}>
                                        {{ $cliente->razonSocial }} - {{ $cliente->cuit }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Factura</label>
                            <div class="grid grid-cols-3 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="tipoFactura" value="A" class="peer sr-only" {{ $factura->tipoFactura == 'A' ? 'checked' : '' }}>
                                    <div class="text-center py-2 border rounded-md peer-checked:bg-gray-900 peer-checked:text-white peer-checked:border-gray-900 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">A</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="tipoFactura" value="B" class="peer sr-only" {{ $factura->tipoFactura == 'B' ? 'checked' : '' }}>
                                    <div class="text-center py-2 border rounded-md peer-checked:bg-gray-900 peer-checked:text-white peer-checked:border-gray-900 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">B</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="tipoFactura" value="C" class="peer sr-only" {{ $factura->tipoFactura == 'C' ? 'checked' : '' }}>
                                    <div class="text-center py-2 border rounded-md peer-checked:bg-gray-900 peer-checked:text-white peer-checked:border-gray-900 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">C</div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Descuento en Efectivo ($)</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="descuentoEfectivo" id="descuentoEfectivo" 
                                    step="0.01" min="0" 
                                    value="{{ $factura->descuentoEfectivo }}" 
                                    oninput="calcularTotales()"
                                    class="w-full pl-7 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Datos Fijos</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="block text-gray-500 text-xs">Fecha Emisión</span>
                            <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($factura->fechaFactura)->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-gray-500 text-xs">Movimiento Ref.</span>
                            <span class="font-medium text-gray-900">#{{ $factura->movimiento->idMovimiento }}</span>
                            <span class="text-gray-500 text-xs block">{{ $factura->movimiento->almacen->nombreAlmacen }}</span>
                        </div>
                        <div>
                            <span class="block text-gray-500 text-xs">Estado</span>
                            <span class="font-medium {{ $factura->estado == 'PAGADA' ? 'text-green-600' : 'text-amber-600' }}">
                                {{ $factura->estado }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:sticky lg:top-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Nuevos Totales</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span id="displaySubtotal" data-original="{{ $factura->subtotal + $factura->descuentoEfectivo }}">
                                $ {{ number_format($factura->subtotal + $factura->descuentoEfectivo, 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between text-green-600 font-medium">
                            <span>Descuento</span>
                            <span id="displayDescuento">- $ {{ number_format($factura->descuentoEfectivo, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Base Imponible</span>
                            <span id="displayBase">$ {{ number_format($factura->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>IVA (21%)</span>
                            <span id="displayIva">$ {{ number_format($factura->iva, 2) }}</span>
                        </div>
                        <div class="border-t border-gray-100 pt-3 flex justify-between items-end">
                            <span class="font-bold text-gray-900 text-lg">Total</span>
                            <span id="displayTotal" class="font-bold text-gray-900 text-2xl">$ {{ number_format($factura->total, 2) }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col gap-3">
                        <button type="submit" class="w-full bg-gray-900 text-white py-3 rounded-lg hover:bg-gray-800 transition font-medium shadow-sm">
                            Guardar Cambios
                        </button>
                        <a href="{{ route('facturas.index') }}" class="w-full text-center py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition font-medium">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Detalle de Productos</h3>
                        <p class="text-xs text-gray-500 mt-1">Los items no se pueden editar ya que pertenecen a un movimiento de stock cerrado.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cant.</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unitario</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($factura->movimiento->detalles as $detalle)
                                @php
                                    $precioVenta = app('App\Http\Controllers\CotizacionController')->getPrecioActual($detalle->idArticuloMarca);
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $detalle->articuloMarca->articulo->nombreArticulo }}</div>
                                        <div class="text-xs text-gray-500">{{ $detalle->articuloMarca->marca->nombreMarca }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ $detalle->cantidad }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm text-gray-500">
                                        $ {{ number_format($precioVenta, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                        $ {{ number_format($detalle->cantidad * $precioVenta, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function calcularTotales() {
    // Obtenemos el subtotal original guardado en el atributo data-original (sin formato moneda)
    const subtotalSpan = document.getElementById('displaySubtotal');
    const subtotalBase = parseFloat(subtotalSpan.getAttribute('data-original')) || 0;
    
    const descuentoInput = document.getElementById('descuentoEfectivo');
    const descuento = parseFloat(descuentoInput.value) || 0;
    
    // Cálculos
    const baseImponible = Math.max(0, subtotalBase - descuento);
    const iva = baseImponible * 0.21;
    const total = baseImponible + iva;
    
    // Actualizar UI
    document.getElementById('displayDescuento').textContent = '- $ ' + descuento.toLocaleString('es-AR', {minimumFractionDigits: 2});
    document.getElementById('displayBase').textContent = '$ ' + baseImponible.toLocaleString('es-AR', {minimumFractionDigits: 2});
    document.getElementById('displayIva').textContent = '$ ' + iva.toLocaleString('es-AR', {minimumFractionDigits: 2});
    document.getElementById('displayTotal').textContent = '$ ' + total.toLocaleString('es-AR', {minimumFractionDigits: 2});
}
</script>
@endsection