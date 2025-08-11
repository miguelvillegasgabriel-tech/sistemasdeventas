<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompraRequest;
use App\Models\Compra;
use App\Models\Proveedore;
use App\Models\Comprobante;
use App\Models\Producto;
use Exception;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importar el facade DB para usar transacciones
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class compraController extends Controller implements HasMiddleware
{
      public static function middleware(): array {
        return [ 
          new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('ver-compra|crear-compra|mostrar-compra|eliminar-compra'),only:['index']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('crear-compra'), only:['create','store']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('editar-compra'),only:['show']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('eliminar-compra'), only:['destroy']),
        ];
     }

    public function index(): View
    {

        $compras = Compra::with('comprobante','proveedore.persona')
        ->where('estado',1)
        ->latest()
        ->get();
        return view('compra.index', compact('compras'));
    }

    public function create(): View
    {

        $proveedores = Proveedore::whereHas('persona',function($query){
            $query->where('estado',1);
        })->get();
        $comprobantes = Comprobante::all();
        $productos = Producto::where('estado',1)->get();
        return view('compra.create',compact('proveedores','comprobantes','productos'));
    }

    public function store(StoreCompraRequest $request): RedirectResponse
    {

        try {
            
            DB::beginTransaction();
        

            $compra = Compra::create($request->validated());

            //llenar tabla  compra_producto

            //  1.recuperar lo arrays
            $arrayProducto_id = $request->get('arrayidproducto');
            $arrayCantidad = $request->get('arraycantidad');
            $arrayPrecioCompra = $request->get('arraypreciocompra');
            $arrayPrecioVenta = $request->get('arrayprecioventa');

            // 2.realizar el llenado
            $siseArray = count($arrayProducto_id);
            $cont = 0;

            while($cont < $siseArray){

                $compra->productos()->syncWithoutDetaching([

                    $arrayProducto_id[$cont] => [
                        'cantidad' => $arrayCantidad[$cont],
                        'precio_compra' => $arrayPrecioCompra[$cont],
                        'precio_venta' => $arrayPrecioVenta[$cont]
                    ]
                ]);

                // 3.actualizar el stock
                $producto = Producto::find($arrayProducto_id[$cont]);
                $stockactual = $producto->stock;
                $stocknuevo = intval($arrayCantidad[$cont]);

                DB::table('productos')
                ->where('id',$producto->id)
                ->update([
                    'stock' => $stockactual + $stocknuevo
                ]);
                
                $cont++;

            }


            DB::commit();
        } catch (Exception $e) {
            
            DB::rollBack();
        }

        return redirect()->route('compras.index')->with('success','Compra exitosa');
    }

    public function show(Compra $compra): View
    {

        return view('compra.show', compact('compra'));
    }

    public function destroy(string $id): RedirectResponse
    {

        Compra::where('id',$id)
        ->update([
            'estado' => 0
        ]);

        return redirect()->route('compras.index')->with('success', 'Compra eliminada');
    }

}
