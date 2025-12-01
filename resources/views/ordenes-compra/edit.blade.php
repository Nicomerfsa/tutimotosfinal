@extends('layout.base')

@section('title', 'Editar Orden de Compra')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Editar Orden #{{ $orden->comprobanteOC }}</h2>
            <p class="text-sm text-gray-500 mt-1">Modifica los detalles o el proveedor de la orden.</p>
        </div>
        <a href="{{ route('ordenes-compra.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
            <p class="font-bold text-red-700">Por favor corrige los siguientes errores:</p>
            <ul class="list-disc list-inside text-sm text-red-600 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('ordenes-compra.update', $orden->idOrdenCompra) }}" id="ordenForm">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">
                    Datos de la Orden
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                        <select name="idProveedor" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                            <option value="">Seleccionar proveedor</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->idProveedor }}" {{ $orden->idProveedor == $proveedor->idProveedor ? 'selected' : '' }}>
                                    {{ $proveedor->razonSocialProveedor }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if($empresa)
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Facturar a</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $empresa->razonSocial }}</p>
                        <p class="text-xs text-gray-500">CUIT: {{ $empresa->cuit }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado Actual</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $orden->estado == 'PENDIENTE' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $orden->estado }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Items</h3>
                </div>

                <div class="p-6">
                    <div class="hidden md:grid grid-cols-12 gap-4 mb-2 px-2">
                        <div class="col-span-8 text-xs font-medium text-gray-500 uppercase">Producto</div>
                        <div class="col-span-3 text-xs font-medium text-gray-500 uppercase">Cantidad</div>
                        <div class="col-span-1"></div>
                    </div>

                    <div id="productos-container" class="space-y-3">
                        @foreach($orden->detalles as $index => $detalle)
                        <div class="producto-item bg-white md:bg-gray-50 rounded-lg border border-gray-200 p-3 md:border-0 md:p-0">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                <div class="col-span-1 md:col-span-8">
                                    <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Producto</label>
                                    <select name="articulos[{{ $index }}][idArticuloMarca]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                        <option value="">Seleccionar producto</option>
                                        @foreach($articulos as $articulo)
                                            <option value="{{ $articulo->idArticuloMarca }}" {{ $detalle->idArticuloMarca == $articulo->idArticuloMarca ? 'selected' : '' }}>
                                                {{ $articulo->articulo->nombreArticulo }} - {{ $articulo->marca->nombreMarca }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-span-1 md:col-span-3">
                                    <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Cantidad</label>
                                    <input type="number" name="articulos[{{ $index }}][cantidad]" min="1" value="{{ $detalle->cantidad }}" required 
                                        class="cantidad-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Cant.">
                                </div>

                                <div class="col-span-1 flex justify-end md:justify-center">
                                    <button type="button" onclick="eliminarProducto(this)" class="text-gray-400 hover:text-red-500 transition p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <button type="button" onclick="agregarProducto()" class="w-full py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-gray-400 hover:text-gray-700 hover:bg-gray-50 transition flex items-center justify-center gap-2 font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Agregar otro producto
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('ordenes-compra.index') }}" class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                    Cancelar
                </a>
                <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition font-medium shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Guardar Cambios
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Pre-generamos las opciones del select desde Blade para usarlas en JS
    const productOptions = `
        <option value="">Seleccionar producto</option>
        @foreach($articulos as $articulo)
            <option value="{{ $articulo->idArticuloMarca }}">
                {{ addslashes($articulo->articulo->nombreArticulo) }} - {{ addslashes($articulo->marca->nombreMarca) }}
            </option>
        @endforeach
    `;

    let productoCount = {{ $orden->detalles->count() }};

    function agregarProducto() {
        const container = document.getElementById('productos-container');
        const nuevoDiv = document.createElement('div');
        
        nuevoDiv.className = 'producto-item bg-white md:bg-gray-50 rounded-lg border border-gray-200 p-3 md:border-0 md:p-0 mt-3 animate-fade-in';
        
        nuevoDiv.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                <div class="col-span-1 md:col-span-8">
                    <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Producto</label>
                    <select name="articulos[${productoCount}][idArticuloMarca]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                        ${productOptions}
                    </select>
                </div>
                <div class="col-span-1 md:col-span-3">
                    <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Cantidad</label>
                    <input type="number" name="articulos[${productoCount}][cantidad]" min="1" value="1" required 
                        class="cantidad-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Cant.">
                </div>
                <div class="col-span-1 flex justify-end md:justify-center">
                    <button type="button" onclick="eliminarProducto(this)" class="text-gray-400 hover:text-red-500 transition p-2">
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
        } else {
            alert('La orden debe tener al menos un producto.');
        }
    }
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
</style>
@endsection