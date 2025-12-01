<?php

namespace App\Http\Controllers;

use App\Models\OrdenCompra;
use App\Models\DetalleOrdenCompra;
use App\Models\Proveedor;
use App\Models\ArticuloMarca;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdenCompraController extends Controller
{
    public function index(Request $request)
    {
        $query = OrdenCompra::with(['proveedor']);
        
        // Búsqueda por comprobante o proveedor
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('comprobanteOC', 'like', '%' . $search . '%')
                  ->orWhereHas('proveedor', function($q2) use ($search) {
                      $q2->where('razonSocialProveedor', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Filtro por estado
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }
        
        $ordenes = $query->orderBy('fechaOC', 'desc')->paginate(15);
        
        return view('ordenes-compra.index', compact('ordenes'));
    }

    public function create()
    {
        $proveedores = Proveedor::orderBy('razonSocialProveedor')->get();
        $articulos = ArticuloMarca::with(['articulo', 'marca'])->get(); // Removido preciosVenta
        $empresa = Empresa::first();
        
        // Si no hay empresa, crear una por defecto para evitar errores
        if (!$empresa) {
            $empresa = new Empresa([
                'razonSocial' => 'Tuti Motos',
                'cuit' => '30-71123456-7',
                'direccionEmpresa' => 'Brandsen 175',
                'telefonoEmpresa' => '3704-123456',
                'correoEmpresa' => 'contacto@tutimotos.com.ar'
            ]);
        }
        
        return view('ordenes-compra.create', compact('proveedores', 'articulos', 'empresa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idProveedor' => 'required|exists:proveedores,idProveedor',
            'articulos' => 'required|array|min:1',
            'articulos.*.idArticuloMarca' => 'required|exists:articulomarca,idArticuloMarca',
            'articulos.*.cantidad' => 'required|integer|min:1',
            // Precio removido de la validación
        ]);

        DB::beginTransaction();

        try {
            // Generar número de orden de compra
            $ultimaOC = OrdenCompra::orderBy('idOrdenCompra', 'desc')->first();
            $comprobanteOC = 'OC-' . str_pad(($ultimaOC ? $ultimaOC->idOrdenCompra : 0) + 1, 4, '0', STR_PAD_LEFT);

            // Obtener empresa (usar la primera)
            $empresa = Empresa::first();

            // Crear orden de compra
            $ordenCompra = OrdenCompra::create([
                'comprobanteOC' => $comprobanteOC,
                'fechaOC' => now(),
                'estado' => 'PENDIENTE',
                'idProveedor' => $request->idProveedor,
                'idEmpresa' => $empresa ? $empresa->idEmpresa : 1
            ]);

            // Crear detalles de orden de compra
            foreach ($request->articulos as $articulo) {
                DetalleOrdenCompra::create([
                    'idOrdenCompra' => $ordenCompra->idOrdenCompra,
                    'idArticuloMarca' => $articulo['idArticuloMarca'],
                    'cantidad' => $articulo['cantidad'],
                    'precioUnitario' => 0 // Precio fijo en 0
                ]);
            }

            DB::commit();

            return redirect()->route('ordenes-compra.show', $ordenCompra->idOrdenCompra)
                ->with('success', 'Orden de compra creada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la orden de compra: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $orden = OrdenCompra::with([
            'proveedor',
            'detalles.articuloMarca.articulo',
            'detalles.articuloMarca.marca',
            'empresa'
        ])->findOrFail($id);
        
        return view('ordenes-compra.show', compact('orden'));
    }

    public function edit($id)
    {
        $orden = OrdenCompra::with(['detalles'])->findOrFail($id);
        $proveedores = Proveedor::orderBy('razonSocialProveedor')->get();
        $articulos = ArticuloMarca::with(['articulo', 'marca'])->get();
        $empresa = Empresa::first();
        
        return view('ordenes-compra.edit', compact('orden', 'proveedores', 'articulos', 'empresa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'idProveedor' => 'required|exists:proveedores,idProveedor',
            'articulos' => 'required|array|min:1',
            'articulos.*.idArticuloMarca' => 'required|exists:articulomarca,idArticuloMarca',
            'articulos.*.cantidad' => 'required|integer|min:1',
            // Precio removido de la validación
        ]);

        DB::beginTransaction();

        try {
            $orden = OrdenCompra::findOrFail($id);

            // Actualizar orden de compra
            $orden->update([
                'idProveedor' => $request->idProveedor,
            ]);

            // Eliminar detalles antiguos
            DetalleOrdenCompra::where('idOrdenCompra', $id)->delete();

            // Crear nuevos detalles
            foreach ($request->articulos as $articulo) {
                DetalleOrdenCompra::create([
                    'idOrdenCompra' => $orden->idOrdenCompra,
                    'idArticuloMarca' => $articulo['idArticuloMarca'],
                    'cantidad' => $articulo['cantidad'],
                    'precioUnitario' => 0 // Precio fijo en 0
                ]);
            }

            DB::commit();

            return redirect()->route('ordenes-compra.show', $orden->idOrdenCompra)
                ->with('success', 'Orden de compra actualizada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la orden de compra: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $orden = OrdenCompra::findOrFail($id);
        $orden->delete();

        return redirect()->route('ordenes-compra.index')->with('success', 'Orden de compra eliminada correctamente');
    }

    public function cambiarEstado($id, $estado)
    {
        $orden = OrdenCompra::findOrFail($id);
        $orden->update(['estado' => $estado]);

        $estadoTexto = $estado == 'RECIBIDA' ? 'marcada como recibida' : 'cancelada';
        return redirect()->route('ordenes-compra.show', $id)->with('success', "Orden de compra {$estadoTexto} correctamente");
    }

    public function print($id)
    {
        $orden = OrdenCompra::with([
            'proveedor',
            'detalles.articuloMarca.articulo',
            'detalles.articuloMarca.marca',
            'empresa'
        ])->findOrFail($id);
        
        return view('ordenes-compra.print', compact('orden'));
    }

    // MÉTODO ELIMINADO: getPrecioArticulo - Ya no se necesita
}