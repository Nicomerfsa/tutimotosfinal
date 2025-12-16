<?php

namespace App\Http\Controllers;

use App\Models\Remito;
use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RemitoController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('remitos')
            ->join('movimientos', 'remitos.idMovimiento', '=', 'movimientos.idMovimiento')
            ->join('almacenes', 'movimientos.idAlmacen', '=', 'almacenes.idAlmacen')
            ->join('usuarios', 'movimientos.idUsuario', '=', 'usuarios.idUsuario')
            ->select(
                'remitos.*',
                'movimientos.idMovimiento',
                'movimientos.tipoMovimiento', // Solo esta columna existe
                'almacenes.nombreAlmacen',
                'usuarios.usuario'
            );
        
        // Filtro por tipo de remito (que es igual al tipoMovimiento)
        if ($request->has('tipo') && $request->tipo != '') {
            $query->where('remitos.tipoRemito', $request->tipo); // Cambiado a tipoRemito
        }
        
        // Filtro por fecha
        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->where('fechaRemito', '>=', $request->fecha_desde);
        }
        
        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->where('fechaRemito', '<=', $request->fecha_hasta);
        }
        
        $remitos = $query->orderBy('fechaRemito', 'desc')->orderBy('idRemito', 'desc')->paginate(15);
        
        return view('remitos.index', compact('remitos'));
    }

    public function show($id)
    {
        $remito = DB::table('remitos')
            ->join('movimientos', 'remitos.idMovimiento', '=', 'movimientos.idMovimiento')
            ->join('almacenes', 'movimientos.idAlmacen', '=', 'almacenes.idAlmacen')
            ->join('usuarios', 'movimientos.idUsuario', '=', 'usuarios.idUsuario')
            ->where('remitos.idRemito', $id)
            ->select(
                'remitos.*',
                'movimientos.idMovimiento',
                'movimientos.tipoMovimiento',
                'movimientos.fechaMovimiento',
                'movimientos.idUsuario',
                'movimientos.idAlmacen',
                'movimientos.observaciones',
                'almacenes.nombreAlmacen',
                'usuarios.usuario'
            )
            ->first();
            
        if (!$remito) {
            abort(404);
        }
        
        // Obtener detalles del movimiento
        $detalles = DB::table('detmovimiento')
            ->join('articulomarca', 'detmovimiento.idArticuloMarca', '=', 'articulomarca.idArticuloMarca')
            ->join('articulos', 'articulomarca.idArticulo', '=', 'articulos.idArticulo')
            ->join('marcas', 'articulomarca.idMarca', '=', 'marcas.idMarca')
            ->where('detmovimiento.idMovimiento', $remito->idMovimiento)
            ->select(
                'articulos.nombreArticulo',
                'marcas.nombreMarca',
                'detmovimiento.cantidad'
            )
            ->get();
        
        return view('remitos.show', compact('remito', 'detalles'));
    }

    public function print($id)
    {
        $remito = DB::table('remitos')
            ->join('movimientos', 'remitos.idMovimiento', '=', 'movimientos.idMovimiento')
            ->join('almacenes', 'movimientos.idAlmacen', '=', 'almacenes.idAlmacen')
            ->join('usuarios', 'movimientos.idUsuario', '=', 'usuarios.idUsuario')
            ->where('remitos.idRemito', $id)
            ->select(
                'remitos.*',
                'movimientos.idMovimiento',
                'movimientos.tipoMovimiento',
                'movimientos.fechaMovimiento',
                'movimientos.idUsuario',
                'movimientos.idAlmacen',
                'movimientos.observaciones',
                'almacenes.nombreAlmacen',
                'usuarios.usuario'
            )
            ->first();
            
        if (!$remito) {
            abort(404);
        }
        
        // Obtener detalles del movimiento
        $detalles = DB::table('detmovimiento')
            ->join('articulomarca', 'detmovimiento.idArticuloMarca', '=', 'articulomarca.idArticuloMarca')
            ->join('articulos', 'articulomarca.idArticulo', '=', 'articulos.idArticulo')
            ->join('marcas', 'articulomarca.idMarca', '=', 'marcas.idMarca')
            ->where('detmovimiento.idMovimiento', $remito->idMovimiento)
            ->select(
                'articulos.nombreArticulo',
                'marcas.nombreMarca',
                'detmovimiento.cantidad'
            )
            ->get();
        
        return view('remitos.print', compact('remito', 'detalles'));
    }

    /**
     * Obtener texto descriptivo para el tipo de movimiento
     */
    private function getTipoMovimientoTexto($tipoMovimiento)
    {
        $tipos = [
            'ENTRADA' => 'Entrada',
            'SALIDA' => 'Salida'
        ];

        return $tipos[$tipoMovimiento] ?? $tipoMovimiento;
    }
}