@extends('layout.base')

@section('title', 'Nueva Cotización')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Nueva Cotización</h2>
            <p class="text-sm text-gray-500 mt-1">Genera un presupuesto formal para un cliente.</p>
        </div>
        <a href="{{ route('cotizaciones.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
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

    <form method="POST" action="{{ route('cotizaciones.store') }}" id="cotizacionForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Datos del Cliente</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                            <div class="flex gap-2">
                                <div class="relative flex-1" id="clienteSelectContainer">
                                    <select name="idCliente" id="clienteSelect" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm pr-10 cliente-select">
                                        <option value="">Seleccionar cliente</option>
                                        <!-- Opción para cliente anónimo/ocasional -->
                                        @php
                                            $clienteGenerico = App\Models\Cliente::getClienteGenerico();
                                            if (!$clienteGenerico) {
                                                $clienteGenerico = App\Models\Cliente::crearClienteGenerico();
                                            }
                                        @endphp
                                        <option value="{{ $clienteGenerico->idCliente }}" data-es-ocasional="true">
                                            CLIENTE OCASIONAL (Cotización Anónima)
                                        </option>
                                        <option disabled>─────────────────────</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->idCliente }}" {{ request('cliente') == $cliente->idCliente ? 'selected' : '' }}>
                                                {{ $cliente->razonSocial }} - CUIT: {{ $cliente->cuit }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <a href="{{ route('clientes.create') }}" class="flex-shrink-0 p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors" title="Crear Nuevo Cliente" target="_blank">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Validez de la oferta</label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="number" name="validezDias" value="30" min="1" max="365" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm pr-12">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">días</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                            <textarea name="observaciones" rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm" placeholder="Notas internas o condiciones..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:sticky lg:top-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Resumen Económico</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal Neto</span>
                            <span id="displaySubtotal">$ 0.00</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>IVA (21%)</span>
                            <span id="displayIva">$ 0.00</span>
                        </div>
                        <div class="border-t border-gray-100 pt-3 flex justify-between items-end">
                            <span class="font-bold text-gray-900 text-base">Total Final</span>
                            <span id="displayTotal" class="font-bold text-gray-900 text-2xl">$ 0.00</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 space-y-3">
                        <button type="submit" class="w-full bg-gray-900 text-white py-3 rounded-lg hover:bg-gray-800 transition font-medium shadow-sm flex justify-center items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Generar Cotización
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden min-h-[500px] flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Items a Cotizar</h3>
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
                            <!-- Los productos se agregarán aquí dinámicamente -->
                        </div>

                        <button type="button" onclick="agregarProducto()" class="mt-6 w-full py-4 border-2 border-dashed border-gray-300 rounded-xl text-gray-500 hover:border-blue-400 hover:text-blue-600 hover:bg-blue-50 transition flex flex-col items-center justify-center gap-1 group">
                            <svg class="w-8 h-8 text-gray-400 group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span class="font-medium">Agregar producto a la cotización</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- Template para opciones de productos -->
<select id="template-options" class="hidden">
    <option value="">Seleccionar producto</option>
    @foreach($articulos as $articulo)
        @php
            $precioActual = $articulo->preciosVenta->last();
            $precio = $precioActual ? ($precioActual->tieneDescuento && $precioActual->precioDescuento ? $precioActual->precioDescuento : $precioActual->precioVenta) : 0;
        @endphp
        <option value="{{ $articulo->idArticuloMarca }}" data-precio="{{ $precio }}">
            {{ $articulo->articulo->nombreArticulo }} - {{ $articulo->marca->nombreMarca }} 
            @if($precio > 0) (Ref: $ {{ number_format($precio, 2) }}) @endif
        </option>
    @endforeach
</select>

<script>
    // 1. Leemos el HTML de opciones generado por Blade
    const productOptions = document.getElementById('template-options').innerHTML;
    
    let productoCount = 0;

    // Función para inicializar la búsqueda en el dropdown de clientes
    function inicializarBusquedaClientes() {
        const clienteSelect = document.getElementById('clienteSelect');
        const clienteContainer = document.getElementById('clienteSelectContainer');
        const originalOptions = Array.from(clienteSelect.options);
        let isSearchVisible = false;
        let searchInput = null;

        // Crear el contenedor de búsqueda (siempre presente pero oculto inicialmente)
        const searchContainer = document.createElement('div');
        searchContainer.id = 'clienteSearchContainer';
        searchContainer.className = 'hidden absolute top-full left-0 right-0 z-50 bg-white border border-gray-300 rounded-lg shadow-lg mt-1';
        searchContainer.innerHTML = `
            <div class="p-3 border-b border-gray-200">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="clienteSearch" 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm" 
                           placeholder="Buscar cliente por nombre o CUIT...">
                </div>
            </div>
            <div id="searchResults" class="max-h-60 overflow-y-auto">
                <!-- Los resultados de búsqueda aparecerán aquí -->
            </div>
        `;

        clienteContainer.appendChild(searchContainer);
        searchInput = document.getElementById('clienteSearch');

        // Mostrar búsqueda cuando se hace clic en el select
        clienteSelect.addEventListener('click', function(e) {
            e.stopPropagation();
            if (!isSearchVisible) {
                mostrarBusqueda();
            }
        });

        // También mostrar con focus
        clienteSelect.addEventListener('focus', function() {
            if (!isSearchVisible) {
                mostrarBusqueda();
            }
        });

        function mostrarBusqueda() {
            searchContainer.classList.remove('hidden');
            isSearchVisible = true;
            
            // Enfocar el campo de búsqueda después de un pequeño delay
            setTimeout(() => {
                searchInput.focus();
                searchInput.select();
            }, 10);
            
            // Mostrar todas las opciones inicialmente
            mostrarResultadosBusqueda('');
        }

        function ocultarBusqueda() {
            searchContainer.classList.add('hidden');
            isSearchVisible = false;
            
            // Restaurar todas las opciones en el select original
            originalOptions.forEach(option => {
                option.style.display = '';
                option.hidden = false;
            });
        }

        // Búsqueda en tiempo real
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            mostrarResultadosBusqueda(searchTerm);
        });

        function mostrarResultadosBusqueda(searchTerm) {
            const resultsContainer = document.getElementById('searchResults');
            resultsContainer.innerHTML = '';

            const filteredOptions = originalOptions.filter(option => {
                if (option.value === '' || option.disabled) return false;
                const text = option.text.toLowerCase();
                return text.includes(searchTerm);
            });

            if (filteredOptions.length === 0) {
                resultsContainer.innerHTML = `
                    <div class="p-4 text-center text-gray-500 text-sm">
                        No se encontraron clientes que coincidan con "${searchTerm}"
                    </div>
                `;
            } else {
                filteredOptions.forEach(option => {
                    const resultItem = document.createElement('div');
                    resultItem.className = 'p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 search-result-item text-sm';
                    resultItem.textContent = option.text;
                    resultItem.addEventListener('click', function() {
                        clienteSelect.value = option.value;
                        ocultarBusqueda();
                        // Disparar evento change para posibles listeners
                        clienteSelect.dispatchEvent(new Event('change'));
                    });
                    resultsContainer.appendChild(resultItem);
                });
            }
        }

        // Ocultar búsqueda cuando se hace clic fuera
        document.addEventListener('click', function(e) {
            if (!clienteContainer.contains(e.target) && isSearchVisible) {
                ocultarBusqueda();
            }
        });

        // Manejar tecla Escape
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                ocultarBusqueda();
                clienteSelect.focus();
            }
        });

        // Manejar tecla Enter para seleccionar el primer resultado
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const firstResult = document.querySelector('#searchResults > div:first-child');
                if (firstResult) {
                    firstResult.click();
                }
            }
        });
    }

    function agregarProducto() {
        const container = document.getElementById('productos-container');
        const index = productoCount; 
        const nuevoDiv = document.createElement('div');
        
        nuevoDiv.className = 'producto-item bg-white md:bg-gray-50 rounded-lg border border-gray-200 p-4 md:border-0 md:p-0 animate-fade-in-up';
        
        nuevoDiv.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                
                <div class="col-span-1 md:col-span-5">
                    <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Producto</label>
                    <div class="relative producto-select-container">
                        <select name="articulos[${index}][idArticuloMarca]" 
                                class="articulo-select w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm pr-10" 
                                required 
                                onchange="actualizarPrecio(this, ${index})">
                            ${productOptions}
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="col-span-1 md:col-span-2">
                    <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Cantidad</label>
                    <input type="number" name="articulos[${index}][cantidad]" 
                           min="1" value="1" required 
                           class="cantidad-input w-full text-center rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" 
                           oninput="calcularFila(${index})">
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Unitario</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-xs">$</span>
                        </div>
                        <input type="number" name="articulos[${index}][precioUnitario]" 
                               step="0.01" min="0" value="0" required 
                               class="precio-input w-full pl-5 text-right rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm bg-gray-50" 
                               readonly
                               oninput="calcularFila(${index})">
                    </div>
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
        
        // Inicializar la búsqueda para este producto recién agregado
        inicializarBusquedaProducto(nuevoDiv.querySelector('.producto-select-container'));
        
        productoCount++;
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

        // También mostrar con focus
        productoSelect.addEventListener('focus', function() {
            if (!isSearchVisible) {
                mostrarBusqueda();
            }
        });

        function mostrarBusqueda() {
            // Ocultar otros contenedores de búsqueda abiertos
            document.querySelectorAll('.producto-search-container').forEach(otherContainer => {
                if (otherContainer !== searchContainer) {
                    otherContainer.classList.add('hidden');
                }
            });
            
            searchContainer.classList.remove('hidden');
            isSearchVisible = true;
            
            // Enfocar el campo de búsqueda después de un pequeño delay
            setTimeout(() => {
                searchInput.focus();
                searchInput.select();
            }, 10);
            
            // Mostrar todas las opciones inicialmente
            mostrarResultadosBusqueda('');
        }

        function ocultarBusqueda() {
            searchContainer.classList.add('hidden');
            isSearchVisible = false;
            
            // Restaurar todas las opciones en el select original
            originalOptions.forEach(option => {
                option.style.display = '';
                option.hidden = false;
            });
        }

        // Búsqueda en tiempo real
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
                    <div class="p-4 text-center text-gray-500 text-sm">
                        No se encontraron productos que coincidan con "${searchTerm}"
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
                        // Disparar evento change para actualizar el precio
                        productoSelect.dispatchEvent(new Event('change'));
                    });
                    resultsContainer.appendChild(resultItem);
                });
            }
        }

        // Ocultar búsqueda cuando se hace clic fuera
        document.addEventListener('click', function(e) {
            if (!container.contains(e.target) && isSearchVisible) {
                ocultarBusqueda();
            }
        });

        // Manejar tecla Escape
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                ocultarBusqueda();
                productoSelect.focus();
            }
        });

        // Manejar tecla Enter para seleccionar el primer resultado
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const firstResult = resultsContainer.querySelector('.producto-search-result:first-child');
                if (firstResult) {
                    firstResult.click();
                }
            }
        });
    }

    function eliminarProducto(button) {
        const items = document.querySelectorAll('.producto-item');
        if (items.length > 1) {
            button.closest('.producto-item').remove();
            calcularTotalesGenerales();
        } else {
            alert('Debe haber al menos un producto.');
        }
    }

    function actualizarPrecio(select, index) {
        const option = select.options[select.selectedIndex];
        const precio = option.getAttribute('data-precio');
        const inputPrecio = document.querySelector(`input[name="articulos[${index}][precioUnitario]"]`);
        
        if(precio) {
            inputPrecio.value = precio;
            // Efecto visual para mostrar que se actualizó
            inputPrecio.classList.add('bg-blue-50', 'text-blue-700');
            setTimeout(() => inputPrecio.classList.remove('bg-blue-50', 'text-blue-700'), 300);
        } else {
            inputPrecio.value = 0;
        }
        
        calcularFila(index);
    }

    function calcularFila(index) {
        const cantInput = document.querySelector(`input[name="articulos[${index}][cantidad]"]`);
        const precInput = document.querySelector(`input[name="articulos[${index}][precioUnitario]"]`);
        const subtotalDisplay = document.getElementById(`subtotal-${index}`);

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

    // Inicializar
    document.addEventListener('DOMContentLoaded', () => {
        agregarProducto();
        inicializarBusquedaClientes();
    });
</script>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.3s ease-out forwards;
    }
    
    /* SOLUCIÓN: Cursor normal en todo el contenedor de cliente */
    #clienteSelectContainer,
    #clienteSelectContainer * {
        cursor: default !important;
    }
    
    /* Excepto para los elementos que necesitan cursor pointer */
    .search-result-item {
        cursor: pointer !important;
    }
    
    /* Y para el campo de búsqueda */
    #clienteSearch {
        cursor: text !important;
    }
    
    /* Cursor normal específico para el select */
    .cliente-select {
        cursor: default !important;
    }
    
    /* Tamaño de letra consistente para los resultados de búsqueda */
    .search-result-item {
        font-size: 0.875rem !important; /* text-sm */
        line-height: 1.25rem !important;
    }
    
    /* Tamaño de letra para el mensaje de no resultados */
    #searchResults div.text-sm {
        font-size: 0.875rem !important;
        line-height: 1.25rem !important;
    }
    
    #clienteSearchContainer {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    /* Estilos para la búsqueda de productos */
    .producto-select-container,
    .producto-select-container * {
        cursor: default !important;
    }
    
    .producto-search-result {
        cursor: pointer !important;
    }
    
    .producto-search-input {
        cursor: text !important;
    }
    
    .articulo-select {
        cursor: default !important;
    }
    
    .producto-search-result {
        font-size: 0.875rem !important;
        line-height: 1.25rem !important;
    }
    
    .producto-search-container {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    /* Posicionamiento relativo para el contenedor del select */
    .producto-select-container {
        position: relative;
    }
</style>
@endsection