@extends('layout.base')

@section('title', 'Editar Cotización')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Editar Cotización #{{ $cotizacion->numeroCotizacion }}</h2>
            <p class="text-sm text-gray-500 mt-1">Modifica los items o condiciones de la propuesta.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('cotizaciones.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
            <p class="font-bold text-red-700">Por favor corrige los errores:</p>
            <ul class="list-disc list-inside text-sm text-red-600 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('cotizaciones.update', $cotizacion->idCotizacion) }}" id="cotizacionForm">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Configuración</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                            <div class="flex gap-2">
                                <select name="idCliente" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                    <option value="">Seleccionar cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->idCliente }}" {{ $cotizacion->idCliente == $cliente->idCliente ? 'selected' : '' }}>
                                            {{ $cliente->razonSocial }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Validez (días)</label>
                            <input type="number" name="validezDias" value="{{ $cotizacion->validezDias }}" min="1" max="365" required 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                            <textarea name="observaciones" rows="4" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">{{ $cotizacion->observaciones }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:sticky lg:top-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Resumen</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal Neto</span>
                            <span id="displaySubtotal">$ {{ number_format($cotizacion->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>IVA (21%)</span>
                            <span id="displayIva">$ {{ number_format($cotizacion->iva, 2) }}</span>
                        </div>
                        <div class="border-t border-gray-100 pt-3 flex justify-between items-end">
                            <span class="font-bold text-gray-900 text-base">Total Final</span>
                            <span id="displayTotal" class="font-bold text-gray-900 text-2xl">$ {{ number_format($cotizacion->total, 2) }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col gap-3">
                        <button type="submit" class="w-full bg-gray-900 text-white py-3 rounded-lg hover:bg-gray-800 transition font-medium shadow-sm">
                            Guardar Cambios
                        </button>
                        <a href="{{ route('cotizaciones.index') }}" class="w-full text-center py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition font-medium">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden min-h-[500px] flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Items de la Cotización</h3>
                    </div>

                    <div class="p-6 flex-1">
                        <div class="hidden md:grid grid-cols-12 gap-4 mb-2 px-2">
                            <div class="col-span-5 text-xs font-medium text-gray-500 uppercase">Producto</div>
                            <div class="col-span-2 text-xs font-medium text-gray-500 uppercase text-center">Cant.</div>
                            <div class="col-span-2 text-xs font-medium text-gray-500 uppercase text-right">Unitario</div>
                            <div class="col-span-2 text-xs font-medium text-gray-500 uppercase text-right">Subtotal</div>
                            <div class="col-span-1"></div>
                        </div>

                        <div id="productos-container" class="space-y-3">
                            
                            {{-- Iteramos los detalles EXISTENTES --}}
                            @foreach($cotizacion->detalles as $index => $detalle)
                            <div class="producto-item bg-white md:bg-gray-50 rounded-lg border border-gray-200 p-4 md:border-0 md:p-0">
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                    
                                    <div class="col-span-1 md:col-span-5">
                                        <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Producto</label>
                                        <select name="articulos[{{ $index }}][idArticuloMarca]" 
                                                class="articulo-select w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" 
                                                required 
                                                onchange="actualizarPrecio(this, {{ $index }})">
                                            <option value="">Seleccionar producto</option>
                                            @foreach($articulos as $articulo)
                                                <option value="{{ $articulo->idArticuloMarca }}" 
                                                    {{ $detalle->idArticuloMarca == $articulo->idArticuloMarca ? 'selected' : '' }}
                                                    data-precio="{{ $articulo->preciosVenta->last()->precioVenta ?? 0 }}">
                                                    {{ $articulo->articulo->nombreArticulo }} - {{ $articulo->marca->nombreMarca }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-span-1 md:col-span-2">
                                        <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Cantidad</label>
                                        <input type="number" name="articulos[{{ $index }}][cantidad]" 
                                               value="{{ $detalle->cantidad }}" min="1" required 
                                               class="cantidad-input w-full text-center rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" 
                                               oninput="calcularFila({{ $index }})">
                                    </div>

                                    <div class="col-span-1 md:col-span-2">
                                        <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Unitario</label>
                                        <input type="number" name="articulos[{{ $index }}][precioUnitario]" 
                                               value="{{ $detalle->precioUnitario }}" step="0.01" min="0" required 
                                               class="precio-input w-full text-right rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm bg-gray-50" 
                                               readonly
                                               oninput="calcularFila({{ $index }})">
                                    </div>

                                    <div class="col-span-1 md:col-span-2 text-right font-medium text-gray-900">
                                        <label class="md:hidden text-xs font-bold text-gray-500 mr-2">Subtotal:</label>
                                        <span id="subtotal-{{ $index }}">$ {{ number_format($detalle->cantidad * $detalle->precioUnitario, 2) }}</span>
                                    </div>

                                    <div class="col-span-1 flex justify-end md:justify-center">
                                        <button type="button" onclick="eliminarProducto(this)" class="text-gray-400 hover:text-red-500 transition p-1 rounded hover:bg-red-50">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>

                        <button type="button" onclick="agregarProducto()" class="mt-6 w-full py-4 border-2 border-dashed border-gray-300 rounded-xl text-gray-500 hover:border-blue-400 hover:text-blue-600 hover:bg-blue-50 transition flex flex-col items-center justify-center gap-1">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span class="font-medium">Agregar otro producto</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<select id="template-options" class="hidden">
    <option value="">Seleccionar producto</option>
    @foreach($articulos as $articulo)
        @php
            $precioActual = $articulo->preciosVenta->last();
            $precio = $precioActual ? ($precioActual->tieneDescuento && $precioActual->precioDescuento ? $precioActual->precioDescuento : $precioActual->precioVenta) : 0;
        @endphp
        <option value="{{ $articulo->idArticuloMarca }}" data-precio="{{ $precio }}">
            {{ $articulo->articulo->nombreArticulo }} - {{ $articulo->marca->nombreMarca }}
        </option>
    @endforeach
</select>

<script>
    const productOptions = document.getElementById('template-options').innerHTML;
    
    // Importante: Inicializamos el contador con la cantidad de items existentes
    let productoCount = {{ $cotizacion->detalles->count() }};

    function agregarProducto() {
        const container = document.getElementById('productos-container');
        const index = productoCount;
        const nuevoDiv = document.createElement('div');
        
        nuevoDiv.className = 'producto-item bg-white md:bg-gray-50 rounded-lg border border-gray-200 p-4 md:border-0 md:p-0 mt-3 animate-fade-in';
        
        nuevoDiv.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                <div class="col-span-1 md:col-span-5">
                    <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Producto</label>
                    <select name="articulos[${index}][idArticuloMarca]" class="articulo-select w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required onchange="actualizarPrecio(this, ${index})">
                        ${productOptions}
                    </select>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Cantidad</label>
                    <input type="number" name="articulos[${index}][cantidad]" min="1" value="1" required class="cantidad-input w-full text-center rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" oninput="calcularFila(${index})">
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Unitario</label>
                    <input type="number" name="articulos[${index}][precioUnitario]" step="0.01" min="0" value="0" required class="precio-input w-full text-right rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm bg-gray-50" readonly oninput="calcularFila(${index})">
                </div>
                <div class="col-span-1 md:col-span-2 text-right font-medium text-gray-900">
                    <label class="md:hidden text-xs font-bold text-gray-500 mr-2">Subtotal:</label>
                    <span id="subtotal-${index}">$ 0.00</span>
                </div>
                <div class="col-span-1 flex justify-end md:justify-center">
                    <button type="button" onclick="eliminarProducto(this)" class="text-gray-400 hover:text-red-500 transition p-1 rounded hover:bg-red-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
        `;

        container.appendChild(nuevoDiv);
        productoCount++;
    }

    function eliminarProducto(button) {
        const items = document.querySelectorAll('.producto-item');
        if (items.length > 1) {
            button.closest('.producto-item').remove();
            calcularTotalesGenerales();
        } else {
            alert('La cotización debe tener al menos un producto.');
        }
    }

    function actualizarPrecio(select, index) {
        const option = select.options[select.selectedIndex];
        const precio = option.getAttribute('data-precio');
        // Buscamos el input de precio dentro de la misma fila (row)
        const row = select.closest('.producto-item'); 
        const inputPrecio = row.querySelector('.precio-input');
        
        if(precio) {
            inputPrecio.value = precio;
        } else {
            inputPrecio.value = 0;
        }
        
        calcularFila(index);
    }

    function calcularFila(index) {
        // Usamos querySelector genérico para encontrar el ID específico o el elemento relativo
        // Pero como tenemos el índice, podemos buscar por ID o nombre
        const row = document.querySelector(`input[name="articulos[${index}][cantidad]"]`).closest('.producto-item');
        
        const cantInput = row.querySelector('.cantidad-input');
        const precInput = row.querySelector('.precio-input');
        const subtotalDisplay = row.querySelector(`span[id^="subtotal-"]`); // Busca el span de subtotal en esa fila

        const cantidad = parseFloat(cantInput.value) || 0;
        const precio = parseFloat(precInput.value) || 0;
        const subtotal = cantidad * precio;

        subtotalDisplay.textContent = formatMoney(subtotal);
        calcularTotalesGenerales();
    }

    function calcularTotalesGenerales() {
        let subtotalGeneral = 0;

        document.querySelectorAll('.producto-item').forEach(row => {
            const cant = parseFloat(row.querySelector('.cantidad-input').value) || 0;
            const prec = parseFloat(row.querySelector('.precio-input').value) || 0;
            subtotalGeneral += cant * prec;
        });

        const iva = subtotalGeneral * 0.21;
        const total = subtotalGeneral + iva;

        document.getElementById('displaySubtotal').textContent = formatMoney(subtotalGeneral);
        document.getElementById('displayIva').textContent = formatMoney(iva);
        document.getElementById('displayTotal').textContent = formatMoney(total);
    }

    function formatMoney(amount) {
        return '$ ' + amount.toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    // Recalcular al cargar la página para asegurar que los totales coincidan con los datos de la BD
    document.addEventListener('DOMContentLoaded', () => {
        calcularTotalesGenerales();
    });
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
</style>
@endsection