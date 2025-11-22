@extends('layout.base')

@section('title', 'Detalles del Producto')

@section('content')
<h2>Detalles del Producto</h2>

<a href="{{ route('productos.index') }}">← Volver a Productos</a>

<br><br>

<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <td width="20%"><strong>Nombre:</strong></td>
        <td>{{ $producto->nombreArticulo }}</td>
    </tr>
    <tr>
        <td><strong>Categoría:</strong></td>
        <td>{{ $producto->categoria->nombreCatArticulo ?? 'Sin categoría' }}</td>
    </tr>
    <tr>
        <td><strong>Marca:</strong></td>
        <td>{{ $producto->articulosMarcas->first()->marca->nombreMarca ?? 'Sin marca' }}</td>
    </tr>
    <tr>
        <td><strong>Descripción:</strong></td>
        <td>{{ $producto->descripcionArticulo ?? 'Sin descripción' }}</td>
    </tr>
    <tr>
        <td><strong>Precio Actual:</strong></td>
        <td>
            @php
                $precioActual = $producto->articulosMarcas->first()->preciosVenta->sortByDesc('fechaActualizacion')->first();
            @endphp
            @if($precioActual)
                $ {{ number_format($precioActual->precioVenta, 2) }}
                @if($precioActual->tieneDescuento && $precioActual->precioDescuento)
                    <br><strong>Precio con descuento:</strong> $ {{ number_format($precioActual->precioDescuento, 2) }}
                @endif
            @else
                <span style="color: red;">No definido</span>
            @endif
        </td>
    </tr>
</table>

<br>
<a href="{{ route('productos.edit', $producto->idArticulo) }}">Editar Producto</a>
@if($producto->articulosMarcas->first())
    | <a href="{{ route('precios.editar', $producto->articulosMarcas->first()->idArticuloMarca) }}">Editar Precio</a>
@endif

@endsection