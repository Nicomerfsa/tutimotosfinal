@extends('layout.base')

@section('title', 'Nuevo Producto')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Crear Nuevo Producto</h2>
        <a href="{{ route('productos.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-8">
            <form method="POST" action="{{ route('productos.store') }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Producto</label>
                        <input type="text" name="nombreArticulo" value="{{ old('nombreArticulo') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 shadow-sm transition-colors"
                            placeholder="Ej: Aceite Sintético 10W-40">
                        @error('nombreArticulo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                        <div class="relative categoria-select-container">
                            <select name="idCatArticulo" required
                                class="categoria-select w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 shadow-sm transition-colors pr-10">
                                <option value="">Seleccionar categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->idCatArticulo }}" {{ old('idCatArticulo') == $categoria->idCatArticulo ? 'selected' : '' }}>
                                        {{ $categoria->nombreCatArticulo }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('idCatArticulo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                        <div class="relative marca-select-container">
                            <select name="idMarca" required
                                class="marca-select w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 shadow-sm transition-colors pr-10">
                                <option value="">Seleccionar marca</option>
                                @foreach($marcas as $marca)
                                    <option value="{{ $marca->idMarca }}" {{ old('idMarca') == $marca->idMarca ? 'selected' : '' }}>
                                        {{ $marca->nombreMarca }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('idMarca')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Precio de Venta</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="precioVenta" value="{{ old('precioVenta') }}" step="0.01" min="0" required
                                class="pl-7 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 shadow-sm transition-colors"
                                placeholder="0.00">
                        </div>
                        @error('precioVenta')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center h-full pt-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="tieneDescuento" value="1" id="tieneDescuentoCheckbox"
                                {{ old('tieneDescuento') ? 'checked' : '' }}
                                class="rounded border-gray-300 text-gray-900 shadow-sm focus:border-gray-900 focus:ring-gray-900 h-5 w-5 cursor-pointer">
                            <span class="ml-2 text-sm text-gray-700 font-medium">Aplicar descuento promocional</span>
                        </label>
                    </div>

                    <div id="precioDescuentoRow" class="hidden md:col-span-2 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Precio con Descuento</label>
                        <div class="relative rounded-md shadow-sm max-w-xs">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="precioDescuento" value="{{ old('precioDescuento') }}" step="0.01" min="0"
                                class="pl-7 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 shadow-sm transition-colors"
                                placeholder="0.00">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Este precio anulará al precio de venta normal si está activo.</p>
                        @error('precioDescuento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="descripcionArticulo" rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 shadow-sm transition-colors"
                            placeholder="Detalles técnicos del producto...">{{ old('descripcionArticulo') }}</textarea>
                    </div>

                </div>

                <div class="mt-8 flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                    <a href="{{ route('productos.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                        Crear Producto
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para inicializar la búsqueda en los selects
    function inicializarBusquedaSelect(container, placeholder) {
        const select = container.querySelector('select');
        const originalOptions = Array.from(select.options);
        let isSearchVisible = false;
        let searchInput = null;

        // Crear el contenedor de búsqueda
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
                <!-- Los resultados de búsqueda aparecerán aquí -->
            </div>
        `;

        container.appendChild(searchContainer);
        searchInput = container.querySelector('.search-input');
        const resultsContainer = container.querySelector('.search-results');

        // Mostrar búsqueda cuando se hace clic en el select
        select.addEventListener('click', function(e) {
            e.stopPropagation();
            if (!isSearchVisible) {
                mostrarBusqueda();
            }
        });

        // También mostrar con focus
        select.addEventListener('focus', function() {
            if (!isSearchVisible) {
                mostrarBusqueda();
            }
        });

        function mostrarBusqueda() {
            // Ocultar otros contenedores de búsqueda abiertos
            document.querySelectorAll('.search-container').forEach(otherContainer => {
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
                        No se encontraron resultados para "${searchTerm}"
                    </div>
                `;
            } else {
                filteredOptions.forEach(option => {
                    const resultItem = document.createElement('div');
                    resultItem.className = 'p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 search-result text-sm';
                    resultItem.textContent = option.text;
                    resultItem.addEventListener('click', function() {
                        select.value = option.value;
                        ocultarBusqueda();
                        // Disparar evento change para posibles listeners
                        select.dispatchEvent(new Event('change'));
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
                select.focus();
            }
        });

        // Manejar tecla Enter para seleccionar el primer resultado
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const firstResult = resultsContainer.querySelector('.search-result:first-child');
                if (firstResult) {
                    firstResult.click();
                }
            }
        });
    }

    // Inicializar búsqueda para categoría y marca
    const categoriaContainer = document.querySelector('.categoria-select-container');
    const marcaContainer = document.querySelector('.marca-select-container');

    if (categoriaContainer) {
        inicializarBusquedaSelect(categoriaContainer, 'Buscar categoría...');
    }

    if (marcaContainer) {
        inicializarBusquedaSelect(marcaContainer, 'Buscar marca...');
    }

    // Funcionalidad del checkbox de descuento (existente)
    const descuentoCheckbox = document.getElementById('tieneDescuentoCheckbox');
    const descuentoContainer = document.getElementById('precioDescuentoRow');
    
    function toggleDescuentoField() {
        if (descuentoCheckbox.checked) {
            descuentoContainer.classList.remove('hidden');
            // Opcional: enfocar el input cuando aparece
            const input = descuentoContainer.querySelector('input');
            if(input && !input.value) input.focus();
        } else {
            descuentoContainer.classList.add('hidden');
        }
    }
    
    // Estado inicial
    toggleDescuentoField();
    
    // Cambiar cuando se modifique el checkbox
    descuentoCheckbox.addEventListener('change', toggleDescuentoField);
});
</script>

<style>
    /* Posicionamiento relativo para los contenedores de select */
    .categoria-select-container,
    .marca-select-container {
        position: relative;
    }
    
    /* Cursor normal en los contenedores */
    .categoria-select-container,
    .marca-select-container,
    .categoria-select-container *,
    .marca-select-container * {
        cursor: default !important;
    }
    
    /* Cursor pointer para los resultados de búsqueda */
    .search-result {
        cursor: pointer !important;
    }
    
    /* Cursor text para el campo de búsqueda */
    .search-input {
        cursor: text !important;
    }
    
    /* Cursor normal específico para los selects */
    .categoria-select,
    .marca-select {
        cursor: default !important;
    }
    
    /* Tamaño de letra consistente para los resultados de búsqueda */
    .search-result {
        font-size: 0.875rem !important;
        line-height: 1.25rem !important;
    }
    
    /* Tamaño de letra para el mensaje de no resultados */
    .search-results div.text-sm {
        font-size: 0.875rem !important;
        line-height: 1.25rem !important;
    }
    
    /* Sombra para el contenedor de búsqueda */
    .search-container {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection