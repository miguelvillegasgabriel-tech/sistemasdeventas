<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCaracteristicaRequest;
use App\Http\Requests\UpdateCategoriaRequest;

use App\Models\Categoria; // Importar el modelo Categoria
use App\Models\Caracteristica;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB; // Importar el facade DB para usar transacciones
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class CategoriaController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */
    public static function middleware(): array {
        return [ 
          new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('ver-categoria|crear-categoria|editar-categoria|eliminar-categoria'),only:['index']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('crear-categoria'), only:['create','store']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('editar-categoria'),only:['edit','update']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('eliminar-categoria'), only:['destroy']),
        ];
     } 


    public function index(): View
    {
        $categorias = Categoria::with('caracteristica')->latest()->get();
      
        return view('categoria.index',['categorias'=> $categorias]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('categoria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCaracteristicaRequest $request):RedirectResponse
    {
        try{
        
            DB::beginTransaction();

            $caracteristica = Caracteristica::create($request->validated());
            $caracteristica->categoria()->create([
                'caracteristica_id' => $caracteristica->id
            ]);

            DB::commit();

        }catch(Exception $e){

            DB::rollBack();
        }

        return redirect()->route('categorias.index')->with('success','Categoria Registrada');
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
    public function edit(Categoria $categoria): View
    {
        
        return view('categoria.edit',['categoria'=>$categoria]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriaRequest $request, Categoria $categoria): RedirectResponse
    {
        Caracteristica::where('id', $categoria->caracteristica->id)
        ->update($request->validated());

        return redirect()->route('categorias.index')->with('success', 'Categoria Editada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $message = '';
        $categoria = Categoria::find($id);
        if($categoria->caracteristica->estado == 1){
            Caracteristica::where('id',$categoria->caracteristica->id)
            ->update([
                'estado'=> 0
            ]);
            $message = 'Categoria Eliminada';
        }else{
                Caracteristica::where('id',$categoria->caracteristica->id)
                ->update([
                    'estado'=> 1
                ]);
                $message = 'Categoria Restaurada';

            }



        return redirect()->route('categorias.index')->with('success', $message);

    }
}
