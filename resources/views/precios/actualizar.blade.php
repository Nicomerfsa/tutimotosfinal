@extends('layout.base')

@section('title', 'Actualizar Precios')

@section('content')
<h2>Actualización Masiva de Precios</h2>

<a href="{{ route('precios.index') }}">← Volver a Precios</a>

<br><br>

<form method="POST" action="{{ route('precios.store') }}">
    @csrf
    
    <h3>Lista de Productos para Actualizar</h3>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <tr>
            <th>Producto</th>
            <th>Marca</th>
            <th>Precio Actual</th>
            <th>Nuevo Precio Venta</th>
            <th>Descuento</th>
            <th>Precio con Desc.</th>
        </tr>
        @foreach($articulos as $index => $articulo)
        @php
            $precioActual = $articulo->preciosVenta->sortByDesc('fechaActualizacion')->first();
        @endphp
        <tr>
            <td>{{ $articulo->articulo->nombreArticulo }}</td>
            <td>{{ $articulo->marca->nombreMarca }}</td>
            <td>
                @if($precioActual)
                    $ {{ number_format($precioActual->precioVenta, 2) }}
                @else
                    <span style="color: red;">No definido</span>
                @endif
            </td>
            <td>
                <input type="hidden" name="precios[{{ $index }}][idArticuloMarca]" value="{{ $articulo->idArticuloMarca }}">
                <input type="number" name="precios[{{ $index }}][precioVenta]" 
                       step="0.01" min="0" 
                       value="{{ $precioActual ? $precioActual->precioVenta : 0 }}" 
                       required style="width: 100px;">
            </td>
            <td>
                <label>
                    <input type="checkbox" name="precios[{{ $index }}][tieneDescuento]" value="1" 
                           {{ $precioActual && $precioActual->tieneDescuento ? 'checked' : '' }}
                           class="descuento-checkbox" data-index="{{ $index }}">
                    Aplicar descuento
                </label>
            </td>
            <td>
                <input type="number" name="precios[{{ $index }}][precioDescuento]" 
                       step="0.01" min="0" 
                       value="{{ $precioActual && $precioActual->tieneDescuento ? $precioActual->precioDescuento : '' }}" 
                       style="width: 100px;" 
                       placeholder="Precio descuento"
                       id="precioDescuento-{{ $index }}"
                       {{ $precioActual && $precioActual->tieneDescuento ? '' : 'disabled' }}>
            </td>
        </tr>
        @endforeach
    </table>

    <br>
    <button type="submit">Actualizar Todos los Precios</button>
    <a href="{{ route('precios.index') }}">Cancelar</a>
</form>

<br>
<p><strong>Nota:</strong> Esta acción actualizará los registros existentes de precios manteniendo el historial.</p>

@if(session('success'))
    <div style="color: green;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="color: red;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.descuento-checkbox').forEach(function(checkbox) {
        const index = checkbox.getAttribute('data-index');
        const precioDescuentoInput = document.getElementById('precioDescuento-' + index);
        
        function toggleDescuentoField() {
            if (checkbox.checked) {
                precioDescuentoInput.disabled = false;
                precioDescuentoInput.required = true;
            } else {
                precioDescuentoInput.disabled = true;
                precioDescuentoInput.required = false;
                precioDescuentoInput.value = '';
            }
        }
        
        toggleDescuentoField();
        
        checkbox.addEventListener('change', toggleDescuentoField);
    });
});
</script>
@endsection