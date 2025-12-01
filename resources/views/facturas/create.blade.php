@extends('layout.base')

@section('title', 'Nueva Factura')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Nueva Factura</h2>
            <p class="text-sm text-gray-500 mt-1">Generar comprobante fiscal a partir de una salida de stock.</p>
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

    <form method="POST" action="{{ route('facturas.store') }}" id="facturaForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Datos del Comprobante</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Movimiento de Salida</label>
                            <select name="idMovimiento" id="idMovimiento" required onchange="cargarDetallesMovimiento()" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                <option value="">Seleccionar movimiento...</option>
                                @foreach($movimientos as $movimiento)
                                    <option value="{{ $movimiento->idMovimiento }}">
                                        #{{ $movimiento->idMovimiento }} - {{ \Carbon\Carbon::parse($movimiento->fechaMovimiento)->format('d/m/Y') }} ({{ $movimiento->almacen->nombreAlmacen }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Selecciona para cargar los items automáticamente.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                            <select name="idCliente" id="idCliente" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                <option value="">Seleccionar cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->idCliente }}">
                                        {{ $cliente->razonSocial }} - {{ $cliente->cuit }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Factura</label>
                            <div class="grid grid-cols-3 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="tipoFactura" value="A" class="peer sr-only">
                                    <div class="text-center py-2 border rounded-md peer-checked:bg-gray-900 peer-checked:text-white peer-checked:border-gray-900 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">A</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="tipoFactura" value="B" class="peer sr-only" checked>
                                    <div class="text-center py-2 border rounded-md peer-checked:bg-gray-900 peer-checked:text-white peer-checked:border-gray-900 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">B</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="tipoFactura" value="C" class="peer sr-only">
                                    <div class="text-center py-2 border rounded-md peer-checked:bg-gray-900 peer-checked:text-white peer-checked:border-gray-900 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">C</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:sticky lg:top-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Resumen Económico</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Descuento en Efectivo ($)</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="descuentoEfectivo" id="descuentoEfectivo" step="0.01" min="0" value="0" oninput="calcularTotales()"
                                    class="w-full pl-7 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>
                        </div>

                        <div class="border-t border-gray-100 my-3"></div>

                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Subtotal</span>
                            <span id="displaySubtotal">$ 0.00</span>
                        </div>
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Descuento</span>
                            <span id="displayDescuento">- $ 0.00</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Base Imponible</span>
                            <span id="displayBase">$ 0.00</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>IVA (21%)</span>
                            <span id="displayIva">$ 0.00</span>
                        </div>
                        
                        <div class="border-t border-gray-100 pt-3 flex justify-between items-end">
                            <span class="font-bold text-gray-900 text-lg">Total</span>
                            <span id="displayTotal" class="font-bold text-gray-900 text-2xl">$ 0.00</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col gap-3">
                        <button type="submit" class="w-full bg-gray-900 text-white py-3 rounded-lg hover:bg-gray-800 transition font-medium shadow-sm flex justify-center items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Generar Factura
                        </button>
                        <a href="{{ route('facturas.index') }}" class="w-full text-center py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition font-medium">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden min-h-[500px] flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Detalle de Productos</h3>
                        <span id="loadingIndicator" class="hidden text-xs text-blue-600 font-medium flex items-center gap-1">
                            <svg class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Cargando...
                        </span>
                    </div>

                    <div id="detalles-container" class="p-0">
                        <div class="p-12 text-center text-gray-500 flex flex-col items-center">
                            <div class="bg-gray-100 p-4 rounded-full mb-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <p>Seleccione un <strong>Movimiento de Salida</strong> en el panel izquierdo<br>para cargar los productos automáticamente.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

{{-- Script JS --}}
<script>
function cargarDetallesMovimiento() {
    const movimientoId = document.getElementById('idMovimiento').value;
    const container = document.getElementById('detalles-container');
    const loader = document.getElementById('loadingIndicator');
    
    // Reset
    if (!movimientoId) {
        container.innerHTML = `
            <div class="p-12 text-center text-gray-500 flex flex-col items-center">
                <div class="bg-gray-100 p-4 rounded-full mb-3"><svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg></div>
                <p>Seleccione un <strong>Movimiento de Salida</strong>.</p>
            </div>`;
        actualizarTotalesUI(0);
        return;
    }
    
    // Mostrar loading
    loader.classList.remove('hidden');
    container.classList.add('opacity-50');
    
    fetch(`/facturas/movimiento/${movimientoId}/detalles`)
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            return response.json();
        })
        .then(data => {
            let html = '';
            
            if (data.detalles && data.detalles.length > 0) {
                // Construir Tabla con estilos Tailwind
                html += `
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
                        <tbody class="bg-white divide-y divide-gray-200">`;
                
                data.detalles.forEach(detalle => {
                    const precioUnitario = parseFloat(detalle.precioUnitario) || 0;
                    const subtotal = parseFloat(detalle.subtotal) || 0;
                    
                    html += `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">${detalle.producto || 'Producto no disponible'}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                ${detalle.cantidad || 0}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                $ ${precioUnitario.toFixed(2)}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                $ ${subtotal.toFixed(2)}
                            </td>
                        </tr>`;
                });

                html += `</tbody></table></div>`;
            } else {
                html = `<div class="p-8 text-center text-red-500">No hay productos asociados a este movimiento.</div>`;
            }
            
            container.innerHTML = html;
            
            // Guardamos el subtotal "bruto" en un atributo data del contenedor para usarlo en cálculos
            const subtotal = parseFloat(data.subtotal) || 0;
            container.setAttribute('data-subtotal', subtotal);
            
            actualizarTotalesUI(subtotal);
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = `<div class="p-8 text-center text-red-600 bg-red-50 m-4 rounded-lg">
                <p class="font-bold">Error al cargar datos</p>
                <p class="text-sm">${error.message}</p>
            </div>`;
            actualizarTotalesUI(0);
        })
        .finally(() => {
            loader.classList.add('hidden');
            container.classList.remove('opacity-50');
        });
}

function calcularTotales() {
    // Recuperamos el subtotal base guardado en el contenedor (no del texto visible)
    const container = document.getElementById('detalles-container');
    const subtotalBase = parseFloat(container.getAttribute('data-subtotal')) || 0;
    
    actualizarTotalesUI(subtotalBase);
}

function actualizarTotalesUI(subtotal) {
    const descuentoInput = document.getElementById('descuentoEfectivo');
    const descuento = parseFloat(descuentoInput.value) || 0;
    
    // Lógica de negocio: Base Imponible = Subtotal - Descuento
    const baseImponible = Math.max(0, subtotal - descuento);
    
    // IVA (21% sobre la base imponible)
    const iva = baseImponible * 0.21;
    
    // Total Final
    const total = baseImponible + iva;
    
    // Actualizar UI
    document.getElementById('displaySubtotal').textContent = '$ ' + subtotal.toLocaleString('es-AR', {minimumFractionDigits: 2});
    document.getElementById('displayDescuento').textContent = '- $ ' + descuento.toLocaleString('es-AR', {minimumFractionDigits: 2});
    document.getElementById('displayBase').textContent = '$ ' + baseImponible.toLocaleString('es-AR', {minimumFractionDigits: 2});
    document.getElementById('displayIva').textContent = '$ ' + iva.toLocaleString('es-AR', {minimumFractionDigits: 2});
    document.getElementById('displayTotal').textContent = '$ ' + total.toLocaleString('es-AR', {minimumFractionDigits: 2});
}
</script>
@endsection