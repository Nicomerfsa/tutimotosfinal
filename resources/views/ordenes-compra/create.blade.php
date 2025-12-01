@extends('layout.base')

@section('title', 'Nueva Orden de Compra')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Crear Nueva Orden</h2>
            <p class="text-sm text-gray-500 mt-1">Genera un pedido a proveedor para reabastecer stock.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('ordenes-compra.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm leading-5 font-medium text-red-800">Se encontraron errores:</h3>
                    <ul class="list-disc list-inside text-sm text-red-700 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('ordenes-compra.store') }}" id="ordenForm">
        @csrf
        
        <div class="space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Datos Generales</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                        <div class="flex gap-2">
                            <select name="idProveedor" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                <option value="">Seleccionar proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->idProveedor }}">
                                        {{ $proveedor->razonSocialProveedor }}
                                    </option>
                                @endforeach
                            </select>
                            <a href="{{ route('proveedores.create') }}" class="flex-shrink-0 p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200" title="Crear Nuevo Proveedor">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </a>
                        </div>
                    </div>

                    @if($empresa)
                    <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                        <label class="block text-xs font-bold text-blue-800 uppercase">Facturar A</label>
                        <p class="text-sm text-blue-900 font-medium">{{ $empresa->razonSocial }}</p>
                        <p class="text-xs text-blue-600">CUIT: {{ $empresa->cuit }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Productos a Solicitar</h3>
                </div>

                <div class="p-6">
                    <div class="hidden md:grid grid-cols-12 gap-4 mb-2 px-2">
                        <div class="col-span-8 text-xs font-medium text-gray-500 uppercase">Producto</div>
                        <div class="col-span-3 text-xs font-medium text-gray-500 uppercase">Cantidad</div>
                        <div class="col-span-1"></div>
                    </div>

                    <div id="productos-container" class="space-y-3">
                    </div>

                    <button type="button" onclick="agregarProducto()" class="mt-6 w-full py-4 border-2 border-dashed border-gray-300 rounded-xl text-gray-500 hover:border-blue-400 hover:text-blue-600 hover:bg-blue-50 transition flex flex-col items-center justify-center gap-1 group">
                        <svg class="w-8 h-8 text-gray-400 group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="font-medium">Agregar otro producto</span>
                    </button>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="flex justify-center items-center gap-2 px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Confirmar Orden
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // 1. Guardamos las opciones del select en una variable JS limpia
    // Usamos addslashes para evitar romper el string JS si hay comillas en los nombres
    const productOptions = `
        <option value="">Seleccionar producto</option>
        @foreach($articulos as $articulo)
            <option value="{{ $articulo->idArticuloMarca }}">
                {{ addslashes($articulo->articulo->nombreArticulo) }} - {{ addslashes($articulo->marca->nombreMarca) }}
            </option>
        @endforeach
    `;

    let productoCount = 0;

    function agregarProducto() {
        const container = document.getElementById('productos-container');
        const index = productoCount; // Capturamos valor actual
        const nuevoDiv = document.createElement('div');
        
        nuevoDiv.className = 'producto-item bg-white md:bg-gray-50 rounded-lg border border-gray-200 p-3 md:border-0 md:p-0 animate-fade-in-up';
        
        nuevoDiv.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
                
                <div class="col-span-1 md:col-span-8">
                    <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Producto</label>
                    <select name="articulos[${index}][idArticuloMarca]" 
                            class="articulo-select w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" 
                            required>
                        ${productOptions}
                    </select>
                </div>
                
                <div class="col-span-1 md:col-span-3">
                    <label class="md:hidden text-xs font-bold text-gray-500 mb-1 block">Cantidad</label>
                    <input type="number" name="articulos[${index}][cantidad]" 
                           min="1" value="1" required 
                           class="cantidad-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" 
                           placeholder="1">
                </div>

                <div class="col-span-1 flex justify-end md:justify-center pt-1">
                    <button type="button" onclick="eliminarProducto(this)" class="text-gray-400 hover:text-red-500 transition p-1 rounded hover:bg-red-50" title="Quitar fila">
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

    // Validación al enviar (Submit Listener)
    document.getElementById('ordenForm').addEventListener('submit', function(e) {
        let errorMessage = '';
        let hasErrors = false;

        // Validar Cantidades
        const cantidades = document.querySelectorAll('.cantidad-input');
        cantidades.forEach(input => {
            if (parseInt(input.value) < 1) {
                errorMessage = '⚠️ Todas las cantidades deben ser al menos 1.';
                hasErrors = true;
            }
        });

        if (hasErrors) {
            e.preventDefault(); // Detener envío
            alert(errorMessage);
        }
    });

    // Iniciar con 1 producto vacío
    document.addEventListener('DOMContentLoaded', () => {
        agregarProducto();
    });
</script>

<style>
    /* Transiciones suaves */
    .transition { transition-property: all; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.3s ease-out forwards;
    }
</style>
@endsection