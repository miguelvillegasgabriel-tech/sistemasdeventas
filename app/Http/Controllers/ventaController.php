<?php


namespace App\Http\Controllers;

use App\Http\Requests\StoreVentaRequest;

use App\Models\Venta;
use App\Models\Comprobante;
use App\Models\Producto;
use App\Models\Cliente;
use Exception;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB; // Importar el facade DB para usar transacciones
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ventaController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
        return [ 
          new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('ver-venta|crear-venta|mostrar-venta|eliminar-venta'),only:['index']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('crear-venta'), only:['create','store']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('editar-venta'),only:['show']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('eliminar-venta'), only:['destroy']),
        ];
     }

    //  public function __construct(){

           
    //         $this->middleware('permission:ver-venta|crear-venta|mostrar-venta|eliminar-venta', ['only' => ['index']]);
    //         $this->middleware('permission:crear-venta', ['only' => ['create','store']]);
    //         $this->middleware('permission:editar-venta', ['only' => ['show']]);
    //         $this->middleware('permission:eliminar-venta', ['only' => ['destroy']]);
    // } 

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $ventas = Venta::with(['comprobante','cliente.persona','user'])
        ->where('estado',1)
        ->latest()
        ->get();

        return view('venta.index',compact('ventas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        $subquery = DB::table('compra_producto')
            ->select('producto_id', DB::raw('MAX(created_at) as max_created_at'))
            ->groupBy('producto_id');
            

        $productos = Producto::join('compra_producto as cpr', function ($join) use ($subquery){
            $join->on('cpr.producto_id', '=', 'productos.id')
                ->whereIn('cpr.created_at', function ($query) use ($subquery){
                    $query->select('max_created_at')
                        ->fromSub($subquery, 'subquery')
                        ->whereRaw('subquery.producto_id = cpr.producto_id');
                });
        })

            ->select('productos.nombre', 'productos.id', 'productos.stock', 'cpr.precio_venta')
            ->where('productos.estado',1)
            ->where('productos.stock', '>',0)
            ->get();


        $clientes = Cliente::whereHas('persona',function ($query){
            $query->where('estado',1);
        })->get();
        $comprobantes = Comprobante::all();
        return view('venta.create', compact('productos','clientes','comprobantes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVentaRequest $request) : RedirectResponse
    {
        try {
            DB::beginTransaction();

            //Llenar mi tabla venta
            $venta = Venta::create($request->validated());

            //llenar mi tabla venta_producto
            //1.Recuepar Arrays
            $arrayProducto_id = $request->get('arrayidproducto');
            $arrayCantidad = $request->get('arraycantidad');
            $arrayPrecioVenta = $request->get('arrayprecioventa');
            $arrayDescuento = $request->get('arraydescuento');

            //2.Realizar llenado
            $siseArray =  count($arrayProducto_id);
            $cont = 0;

            while($cont < $siseArray){
                $venta->producto()->syncWithoutDetaching([
                    $arrayProducto_id[$cont] => [
                        'cantidad' => $arrayCantidad[$cont],
                        'precio_venta' => $arrayPrecioVenta[$cont],
                        'descuento' => $arrayDescuento[$cont]
                    ]

                ]);

                $producto = Producto::find($arrayProducto_id[$cont]);
                $stockActual = $producto->stock;
                $cantidad = intval($arrayCantidad[$cont]);

                DB::table('productos')
                ->where('id', $producto->id)
                ->update([
                    'stock' => $stockActual - $cantidad
                ]);

                $cont++;
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('ventas.index')->with('success','Venta exitosa');

    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta) : View
    {
        return view('venta.show',compact('venta'));    
    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        Venta::where('id',$id)
        ->update([
            'estado' => 0
        ]);

        return redirect()->route('ventas.index')->with('success', 'Venta eliminada'); 
    }
}
