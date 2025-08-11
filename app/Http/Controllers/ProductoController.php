<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca; // Asegúrate de importar el modelo Marca
use App\Models\Presentacione; // Asegúrate de importar el modelo Marca
use App\Models\Categoria; // Asegúrate de importar el modelo Marca
use App\Models\Producto;



use App\Controllers\CategoriaController;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Http\Requests\UpdateCategoriatRequest;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB; // Importar el facade DB para usar transacciones
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;




class ProductoController extends Controller implements HasMiddleware
{
     public static function middleware(): array {
        return [ 
          new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('ver-producto|crear-producto|editar-producto|eliminar-producto'),only:['index']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('crear-producto'), only:['create','store']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('editar-producto'),only:['edit','update']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('eliminar-producto'), only:['destroy']),
        ];
     }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $productos = Producto::with(['categoria.caracteristica','marca.caracteristica','presentacione.caracteristica'])->latest()->get();
        return view('producto.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        $marcas = Marca::join('caracteristicas as c', 'marcas.caracteristica_id', '=', 'c.id')
        ->select('marcas.id as id', 'c.nombre as nombre')
        ->where('c.estado',1)
        ->get();

        $presentaciones = Presentacione::join('caracteristicas as c', 'presentaciones.caracteristica_id', '=', 'c.id')
        ->select('presentaciones.id as id', 'c.nombre as nombre' )
        ->where('c.estado',1)
        ->get();
        

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
        ->select('categorias.id as id', 'c.nombre as nombre')
        ->where('c.estado',1)
        ->get();

        

        return view('producto.create', compact('marcas','presentaciones','categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request) : RedirectResponse
    {

        // dd($request->all());  // Esto detendrá la ejecución y mostrará los datos enviados

        try{
            DB::beginTransaction();

            $producto = new Producto();
            if($request->hasFile('img_path')){
                   

                $name =  $producto->handleUploadImage($request->file('img_path'));
            }else{
                $name = null;
            }

                $producto->fill([
                    'codigo' => $request->codigo,
                    'nombre' => $request->nombre,
                    'descripcion' => $request->descripcion,
                    'fecha_vencimiento' => $request->fecha_vencimiento,
                    'img_path' => $name,
                    'marca_id' => $request->marca_id,
                    'presentacione_id' => $request->presentacione_id

                ]);

                $producto->save();

                //Rellenar la tabla CategoriaProducto

                $categorias = $request->get('categorias');
                $producto->categoria()->attach($categorias);


                DB::commit();

           
        }catch(Exception $e){

            DB::rollBack();
        

        }   

        return redirect()->route('productos.index')->with('success','producto registrado');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto ) : View
    {
        $marcas = Marca::join('caracteristicas as c', 'marcas.caracteristica_id', '=', 'c.id')
        ->select('marcas.id as id', 'c.nombre as nombre')
        ->where('c.estado',1)
        ->get();

        $presentaciones = Presentacione::join('caracteristicas as c', 'presentaciones.caracteristica_id', '=', 'c.id')
        ->select('presentaciones.id as id', 'c.nombre as nombre' )
        ->where('c.estado',1)
        ->get();
        

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
        ->select('categorias.id as id', 'c.nombre as nombre')
        ->where('c.estado',1)
        ->get();



        return view('producto.edit', compact('producto', 'marcas','presentaciones', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto  $producto) : RedirectResponse
    {
        try{
            DB::beginTransaction();

          
            if($request->hasFile('img_path')){

                $name =  $producto->handleUploadImage($request->file('img_path'));

                //eliminar img storage
                if (Storage::disk('public')->exists('productos/' . $producto->img_path)) {
                    Storage::disk('public')->delete('productos/' . $producto->img_path);
                }

            }else{
                $name = $producto->img_path;
            }

                $producto->fill([
                    'codigo' => $request->codigo,
                    'nombre' => $request->nombre,
                    'descripcion' => $request->descripcion,
                    'fecha_vencimiento' => $request->fecha_vencimiento,
                    'img_path' => $name,
                    'marca_id' => $request->marca_id,
                    'presentacione_id' => $request->presentacione_id

                ]);

                $producto->save();

                //Rellenar la tabla CategoriaProducto

                $categorias = $request->get('categorias');
                $producto->categoria()->sync($categorias);


                DB::commit();

           
        }catch(Exception $e){

            DB::rollBack();
        

        }   

        return redirect()->route('productos.index')->with('success','Producto Editado');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) : RedirectResponse
    {
        $message = '';
        $producto = producto::find($id);
        if ($producto->estado == 1) {
            Producto::where('id', $producto->id)
                ->update([
                    'estado' => 0
                ]);
            $message = 'Producto Eliminado';
        } else {
            Producto::where('id', $producto->id)
                ->update([
                    'estado' => 1
                ]);
            $message = 'Producto restaurado';
        }

        return redirect()->route('productos.index')->with('success', $message);
    }
    
}
