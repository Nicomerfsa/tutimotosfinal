@extends('layout.base')

@section('title', 'Nueva Salida de Stock')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Registrar Salida</h2>
            <p class="text-sm text-gray-500 mt-1">Retiro de mercadería, ventas o ajustes negativos.</p>
        </div>
        <a href="{{ route('movimientos.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
            <p class="font-bold text-red-700">Imposible procesar la salida:</p>
            <ul class="list-disc list-inside text-sm text-red-600 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('movimientos.salida.store') }}" id="salidaForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Datos del Movimiento</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Almacén de Origen</label>
                            <div class="relative">
                                <select name="idAlmacen" id="idAlmacen" required 
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm pr-10">
                                    <option value="">Seleccionar almacén...</option>
                                    @foreach($almacenes as $almacen)
                                        <option value="{{ $almacen->idAlmacen }}">{{ $almacen->nombreAlmacen }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">El stock se descontará de esta ubicación.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Salida</label>
                            <select name="tipoSalida" id="tipoSalida" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                <option value="VENTA">Venta</option>
                                <option value="CONSUMO_INTERNO">Consumo Interno</option>
                                <option value="ROTURA">Rotura/Pérdida</option>
                                <option value="TRASLADO">Traslado entre Almacenes</option>
                                <option value="DEVOLUCION">Devolución a Proveedor</option>
                                <option value="OTRO">Otro</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de Salida</label>
                            <textarea name="observaciones" id="observaciones" rows="4" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm" 
                                placeholder="Ej: Venta local, rotura, consumo interno..."></textarea>
                        </div>

                        <!-- Cliente asociado (solo para ventas) -->
                        <div id="clienteContainer" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                            <div class="relative cliente-select-container">
                                <select name="idCliente" id="idCliente" 
                                    class="cliente-select w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm pr-10">
                                    <option value="">Seleccionar cliente...</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->idCliente }}">{{ $cliente->razonSocial }} - {{ $cliente->cuit }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:sticky lg:top-6">
                    <div class="space-y-4">
                        <!-- Resumen de productos -->
                        <div id="resumenProductos" class="hidden">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Resumen</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Productos:</span>
                                    <span id="totalProductos" class="font-medium">0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total unidades:</span>
                                    <span id="totalUnidades" class="font-medium">0</span>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-900">Items a retirar:</span>
                                    <span id="itemsCount" class="text-lg font-bold text-blue-600">0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Alerta de stock insuficiente -->
                        <div id="alertaStock" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-xs text-red-800">
                                    <strong>Stock insuficiente</strong> en algunos productos. Verifica las cantidades.
                                </p>
                            </div>
                        </div>

                        <!-- Alerta general -->
                        <div class="flex items-start gap-3 p-3 bg-amber-50 border border-amber-100 rounded-lg">
                            <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <p class="text-xs text-amber-800">
                                Esta acción <strong>descontará stock</strong> inmediatamente. Asegúrate de que el conteo físico coincida.
                            </p>
                        </div>

                        <button type="submit" id="submitBtn" 
                            class="w-full bg-gray-900 text-white py-3 rounded-lg hover:bg-gray-800 transition font-medium shadow-sm flex justify-center items-center gap-2 disabled:bg-gray-400 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Confirmar Salida
                        </button>
                        <a href="{{ route('movimientos.index') }}" class="block w-full text-center mt-3 py-2 text-sm text-gray-600 hover:text-gray-900 transition">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden min-h-[500px] flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Productos a Retirar</h3>
                        <div class="flex items-center gap-2">
                            <span id="contadorProductos" class="text-xs text-gray-500 bg-white px-2 py-1 rounded border border-gray-200">0 productos</span>
                            <span id="contadorUnidades" class="text-xs text-gray-500 bg-white px-2 py-1 rounded border border-gray-200">0 unidades</span>
                        </div>
                    </div>

                    <div class="p-6 flex-1">
                        <div class="hidden md:grid grid-cols-12 gap-4 mb-2 px-2">
                            <div class="col-span-6 text-xs font-medium text-gray-500 uppercase">Producto</div>
                            <div class="col-span-2 text-xs font-medium text-gray-500 uppercase text-center">Disponible</div>
                            <div class="col-span-2 text-xs font-medium text-gray-500 uppercase text-center">Salida</div>
                            <div class="col-span-2 text-xs font-medium text-gray-500 uppercase text-center">Acciones</div>
                        </div>

                        <div id="productos-container" class="space-y-3">
                            <!-- Los productos se agregarán aquí dinámicamente -->
                        </div>

                        <button type="button" onclick="agregarProducto()" 
                            class="mt-6 w-full py-4 border-2 border-dashed border-gray-300 rounded-xl text-gray-500 hover:border-blue-400 hover:text-blue-600 hover:bg-blue-50 transition flex flex-col items-center justify-center gap-1 group">
                            <svg class="w-8 h-8 text-gray-400 group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="font-medium">Agregar producto a la lista</span>
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
        <option value="{{ $articulo->idArticuloMarca }}" 
                data-stock="{{ $articulo->stocks->sum('cantidadActual') }}"
                data-nombre="{{ $articulo->articulo->nombreArticulo }}"
                data-marca="{{ $articulo->marca->nombreMarca }}"
                data-precio="{{ $articulo->preciosVenta->last() ? $articulo->preciosVenta->last()->precioVenta : 0 }}">
            {{ $articulo->articulo->nombreArticulo }} - {{ $articulo->marca->nombreMarca }}
        </option>
    @endforeach
</select>

<script>
    const productOptions = document.getElementById('template-options').innerHTML;
    let productoCount = 0;
    let productosAgregados = new Set(); // Para evitar duplicados

    function agregarProducto() {
        const container = document.getElementById('productos-container');
        const index = productoCount;
        const nuevoDiv = document.createElement('div');
        
        nuevoDiv.className = 'producto-item bg-white md:bg-gray-50 rounded-lg border border-gray-200 p-4 md:border-0 md:p-0 animate-fade-in-up';
        nuevoDiv.dataset.index = index;
        
        nuevoDiv.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                
                <div class="col-span-1 md:col-span-6">
                    <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Producto</label>
                    <div class="relative producto-select-container">
                        <select name="articulos[${index}][idArticuloMarca]" 
                                class="articulo-select w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm pr-10" 
                                required 
                                onchange="actualizarStock(this)">
                            ${productOptions}
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="precio-info hidden mt-1">
                        <span class="text-xs text-gray-500">Precio ref: $<span class="precio-valor">0</span></span>
                    </div>
                </div>
                
                <div class="col-span-1 md:col-span-2 text-center">
                    <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Stock Actual</label>
                    <span class="stock-display inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        -
                    </span>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Cantidad Salida</label>
                    <input type="number" name="articulos[${index}][cantidad]" 
                        min="1" value="1" required 
                        class="cantidad-input w-full text-center rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" 
                        placeholder="Cant"
                        oninput="validarCantidad(this); actualizarResumen()">
                    <div class="alerta-cantidad hidden mt-1">
                        <span class="text-xs text-red-600">Excede stock disponible</span>
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2 flex justify-end md:justify-center gap-2">
                    <button type="button" onclick="duplicarProducto(this)" 
                        class="text-gray-400 hover:text-blue-500 transition p-2 hover:bg-blue-50 rounded-full" 
                        title="Duplicar producto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                    <button type="button" onclick="eliminarProducto(this)" 
                        class="text-gray-400 hover:text-red-500 transition p-2 hover:bg-red-50 rounded-full" 
                        title="Quitar producto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;

        container.appendChild(nuevoDiv);
        productoCount++;
        
        // Inicializar búsqueda para este producto
        inicializarBusquedaProducto(nuevoDiv.querySelector('.producto-select-container'));
        actualizarResumen();
    }

    function duplicarProducto(button) {
        const productoItem = button.closest('.producto-item');
        const select = productoItem.querySelector('.articulo-select');
        const cantidadInput = productoItem.querySelector('.cantidad-input');
        
        if (select.value) {
            agregarProducto();
            
            // Esperar a que se cree el nuevo elemento y copiar los valores
            setTimeout(() => {
                const nuevosItems = document.querySelectorAll('.producto-item');
                const nuevoItem = nuevosItems[nuevosItems.length - 1];
                
                nuevoItem.querySelector('.articulo-select').value = select.value;
                nuevoItem.querySelector('.cantidad-input').value = cantidadInput.value;
                
                // Disparar eventos para actualizar stock y resumen
                nuevoItem.querySelector('.articulo-select').dispatchEvent(new Event('change'));
                actualizarResumen();
            }, 100);
        }
    }

    function eliminarProducto(button) {
        const items = document.querySelectorAll('.producto-item');
        if (items.length > 1) {
            const productoItem = button.closest('.producto-item');
            const select = productoItem.querySelector('.articulo-select');
            
            // Remover de productos agregados si tenía un producto seleccionado
            if (select.value) {
                productosAgregados.delete(select.value);
            }
            
            productoItem.remove();
            actualizarResumen();
        } else {
            alert('Debe haber al menos un producto en la salida.');
        }
    }

    function validarCantidad(input) {
        const row = input.closest('.producto-item');
        const stockBadge = row.querySelector('.stock-display');
        const alerta = row.querySelector('.alerta-cantidad');
        const cantidad = parseInt(input.value) || 0;
        const stock = parseInt(stockBadge.textContent) || 0;
        
        if (cantidad > stock && stock >= 0) {
            input.classList.add('border-red-300', 'bg-red-50');
            alerta.classList.remove('hidden');
            return false;
        } else {
            input.classList.remove('border-red-300', 'bg-red-50');
            alerta.classList.add('hidden');
            return true;
        }
    }

    function actualizarStock(select) {
        const row = select.closest('.producto-item');
        const stockBadge = row.querySelector('.stock-display');
        const precioInfo = row.querySelector('.precio-info');
        const precioValor = row.querySelector('.precio-valor');
        const cantidadInput = row.querySelector('.cantidad-input');
        
        const option = select.options[select.selectedIndex];
        const stock = option.getAttribute('data-stock');
        const precio = option.getAttribute('data-precio');

        if (option.value) {
            // Verificar si el producto ya está agregado
            if (productosAgregados.has(option.value)) {
                alert('Este producto ya fue agregado a la lista.');
                select.value = '';
                stockBadge.textContent = '-';
                stockBadge.className = 'stock-display inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
                precioInfo.classList.add('hidden');
                return;
            }
            
            productosAgregados.add(option.value);
        }

        if (stock) {
            stockBadge.textContent = stock;
            
            // Actualizar color según disponibilidad
            stockBadge.classList.remove('bg-gray-100', 'text-gray-800', 'bg-red-100', 'text-red-800', 'bg-yellow-100', 'text-yellow-800', 'bg-green-100', 'text-green-800');
            
            if (parseInt(stock) <= 0) {
                stockBadge.classList.add('bg-red-100', 'text-red-800');
                cantidadInput.disabled = true;
                cantidadInput.value = '0';
            } else if (parseInt(stock) < 5) {
                stockBadge.classList.add('bg-yellow-100', 'text-yellow-800');
                cantidadInput.disabled = false;
            } else {
                stockBadge.classList.add('bg-green-100', 'text-green-800');
                cantidadInput.disabled = false;
            }

            // Mostrar precio si existe
            if (precio && parseFloat(precio) > 0) {
                precioValor.textContent = parseFloat(precio).toLocaleString('es-AR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                precioInfo.classList.remove('hidden');
            } else {
                precioInfo.classList.add('hidden');
            }
        } else {
            stockBadge.textContent = '-';
            stockBadge.classList.remove('bg-red-100', 'text-red-800', 'bg-green-100', 'text-green-800');
            stockBadge.classList.add('bg-gray-100', 'text-gray-800');
            precioInfo.classList.add('hidden');
            cantidadInput.disabled = false;
        }
        
        validarCantidad(cantidadInput);
        actualizarResumen();
    }

    function actualizarResumen() {
        const items = document.querySelectorAll('.producto-item');
        let totalProductos = 0;
        let totalUnidades = 0;
        let hayStockInsuficiente = false;
        
        items.forEach(item => {
            const select = item.querySelector('.articulo-select');
            const cantidadInput = item.querySelector('.cantidad-input');
            const stockBadge = item.querySelector('.stock-display');
            
            if (select.value) {
                totalProductos++;
                const cantidad = parseInt(cantidadInput.value) || 0;
                totalUnidades += cantidad;
                
                const stock = parseInt(stockBadge.textContent) || 0;
                if (cantidad > stock && stock >= 0) {
                    hayStockInsuficiente = true;
                }
            }
        });
        
        // Actualizar contadores
        document.getElementById('contadorProductos').textContent = `${totalProductos} productos`;
        document.getElementById('contadorUnidades').textContent = `${totalUnidades} unidades`;
        document.getElementById('totalProductos').textContent = totalProductos;
        document.getElementById('totalUnidades').textContent = totalUnidades;
        document.getElementById('itemsCount').textContent = totalUnidades;
        
        // Mostrar/ocultar resumen
        const resumen = document.getElementById('resumenProductos');
        if (totalProductos > 0) {
            resumen.classList.remove('hidden');
        } else {
            resumen.classList.add('hidden');
        }
        
        // Mostrar/ocultar alerta de stock
        const alertaStock = document.getElementById('alertaStock');
        if (hayStockInsuficiente) {
            alertaStock.classList.remove('hidden');
        } else {
            alertaStock.classList.add('hidden');
        }
        
        // Habilitar/deshabilitar botón de envío
        const submitBtn = document.getElementById('submitBtn');
        if (totalProductos > 0 && !hayStockInsuficiente) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }

    function inicializarBusquedaProducto(container) {
        const productoSelect = container.querySelector('.articulo-select');
        const originalOptions = Array.from(productoSelect.options);
        let isSearchVisible = false;
        let searchInput = null;

        // Crear el contenedor de búsqueda
        const searchContainer = document.createElement('div');
        searchContainer.className = 'hidden absolute top-full left-0 right-0 z-50 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 producto-search-container';
        searchContainer.innerHTML = `
            <div class="p-3 border-b border-gray-200">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" class="producto-search-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm" 
                           placeholder="Buscar producto por nombre o marca...">
                </div>
            </div>
            <div class="producto-search-results max-h-60 overflow-y-auto">
                <!-- Los resultados de búsqueda aparecerán aquí -->
            </div>
        `;

        container.appendChild(searchContainer);
        searchInput = container.querySelector('.producto-search-input');
        const resultsContainer = container.querySelector('.producto-search-results');

        // Mostrar búsqueda cuando se hace clic en el select
        productoSelect.addEventListener('click', function(e) {
            e.stopPropagation();
            if (!isSearchVisible) {
                mostrarBusqueda();
            }
        });

        productoSelect.addEventListener('focus', function() {
            if (!isSearchVisible) {
                mostrarBusqueda();
            }
        });

        function mostrarBusqueda() {
            document.querySelectorAll('.producto-search-container').forEach(otherContainer => {
                if (otherContainer !== searchContainer) {
                    otherContainer.classList.add('hidden');
                }
            });
            
            searchContainer.classList.remove('hidden');
            isSearchVisible = true;
            
            setTimeout(() => {
                searchInput.focus();
                searchInput.select();
            }, 10);
            
            mostrarResultadosBusqueda('');
        }

        function ocultarBusqueda() {
            searchContainer.classList.add('hidden');
            isSearchVisible = false;
        }

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            mostrarResultadosBusqueda(searchTerm);
        });

        function mostrarResultadosBusqueda(searchTerm) {
            resultsContainer.innerHTML = '';

            const filteredOptions = originalOptions.filter(option => {
                if (option.value === '' || option.disabled) return false;
                if (productosAgregados.has(option.value)) return false; // Excluir ya agregados
                const text = option.text.toLowerCase();
                return text.includes(searchTerm);
            });

            if (filteredOptions.length === 0) {
                resultsContainer.innerHTML = `
                    <div class="p-4 text-center text-gray-500 text-sm">
                        No se encontraron productos disponibles
                    </div>
                `;
            } else {
                filteredOptions.forEach(option => {
                    const resultItem = document.createElement('div');
                    resultItem.className = 'p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 producto-search-result text-sm';
                    resultItem.textContent = option.text;
                    resultItem.addEventListener('click', function() {
                        productoSelect.value = option.value;
                        ocultarBusqueda();
                        productoSelect.dispatchEvent(new Event('change'));
                    });
                    resultsContainer.appendChild(resultItem);
                });
            }
        }

        document.addEventListener('click', function(e) {
            if (!container.contains(e.target) && isSearchVisible) {
                ocultarBusqueda();
            }
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                ocultarBusqueda();
                productoSelect.focus();
            }
            if (e.key === 'Enter') {
                e.preventDefault();
                const firstResult = resultsContainer.querySelector('.producto-search-result:first-child');
                if (firstResult) {
                    firstResult.click();
                }
            }
        });
    }

    // Controlar visibilidad del campo cliente según tipo de salida
    document.getElementById('tipoSalida').addEventListener('change', function() {
        const clienteContainer = document.getElementById('clienteContainer');
        if (this.value === 'VENTA') {
            clienteContainer.classList.remove('hidden');
        } else {
            clienteContainer.classList.add('hidden');
        }
    });

    // Inicializar
    document.addEventListener('DOMContentLoaded', () => {
        agregarProducto();
        
        // Inicializar búsqueda en almacén
        const almacenContainer = document.querySelector('select[name="idAlmacen"]').parentElement;
        inicializarBusquedaSelect(almacenContainer, 'Buscar almacén...');
        
        // Inicializar búsqueda en cliente
        const clienteContainer = document.querySelector('.cliente-select-container');
        if (clienteContainer) {
            inicializarBusquedaSelect(clienteContainer, 'Buscar cliente...');
        }
    });

    // Función reutilizable para inicializar búsqueda
    function inicializarBusquedaSelect(container, placeholder) {
        const select = container.querySelector('select');
        const originalOptions = Array.from(select.options);
        let isSearchVisible = false;

        const searchContainer = document.createElement('div');
        searchContainer.className = 'hidden absolute top-full left-0 right-0 z-50 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 search-container';
        searchContainer.innerHTML = `
            <div class="p-3 border-b border-gray-200">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" class="search-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm" 
                           placeholder="${placeholder}">
                </div>
            </div>
            <div class="search-results max-h-60 overflow-y-auto">
            </div>
        `;

        container.appendChild(searchContainer);
        const searchInput = container.querySelector('.search-input');
        const resultsContainer = container.querySelector('.search-results');

        select.addEventListener('click', function(e) {
            e.stopPropagation();
            if (!isSearchVisible) {
                mostrarBusqueda();
            }
        });

        select.addEventListener('focus', function() {
            if (!isSearchVisible) {
                mostrarBusqueda();
            }
        });

        function mostrarBusqueda() {
            searchContainer.classList.remove('hidden');
            isSearchVisible = true;
            setTimeout(() => {
                searchInput.focus();
                searchInput.select();
            }, 10);
            mostrarResultadosBusqueda('');
        }

        function ocultarBusqueda() {
            searchContainer.classList.add('hidden');
            isSearchVisible = false;
        }

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            mostrarResultadosBusqueda(searchTerm);
        });

        function mostrarResultadosBusqueda(searchTerm) {
            resultsContainer.innerHTML = '';
            const filteredOptions = originalOptions.filter(option => {
                if (option.value === '' || option.disabled) return false;
                const text = option.text.toLowerCase();
                return text.includes(searchTerm);
            });

            if (filteredOptions.length === 0) {
                resultsContainer.innerHTML = `
                    <div class="p-4 text-center text-gray-500 text-sm">No se encontraron resultados</div>
                `;
            } else {
                filteredOptions.forEach(option => {
                    const resultItem = document.createElement('div');
                    resultItem.className = 'p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 search-result text-sm';
                    resultItem.textContent = option.text;
                    resultItem.addEventListener('click', function() {
                        select.value = option.value;
                        ocultarBusqueda();
                        select.dispatchEvent(new Event('change'));
                    });
                    resultsContainer.appendChild(resultItem);
                });
            }
        }

        document.addEventListener('click', function(e) {
            if (!container.contains(e.target) && isSearchVisible) {
                ocultarBusqueda();
            }
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                ocultarBusqueda();
                select.focus();
            }
            if (e.key === 'Enter') {
                e.preventDefault();
                const firstResult = resultsContainer.querySelector('.search-result:first-child');
                if (firstResult) {
                    firstResult.click();
                }
            }
        });
    }
</script>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.3s ease-out forwards;
    }
    
    .producto-select-container {
        position: relative;
    }
    
    .producto-select-container,
    .producto-select-container * {
        cursor: default !important;
    }
    
    .producto-search-result,
    .search-result {
        cursor: pointer !important;
    }
    
    .producto-search-input,
    .search-input {
        cursor: text !important;
    }
    
    .articulo-select {
        cursor: default !important;
    }
    
    .producto-search-result,
    .search-result {
        font-size: 0.875rem !important;
        line-height: 1.25rem !important;
    }
    
    .producto-search-container,
    .search-container {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection