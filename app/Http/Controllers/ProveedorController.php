<?php

namespace App\Http\Controllers;


use App\Models\Documento;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdateProveedorRequest;
use App\Models\Persona;
use App\Models\Proveedore;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB; // Importar el facade DB para usar transacciones

use Illuminate\Http\Request;

class ProveedorController extends Controller implements HasMiddleware
{
     public static function middleware(): array {
        return [ 
          new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('ver-proveedore|crear-proveedore|editar-proveedore|eliminar-proveedore'),only:['index']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('crear-proveedore'), only:['create','store']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('editar-proveedore'),only:['edit','update']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('eliminar-proveedore'), only:['destroy']),
        ];
     }

    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $proveedores = Proveedore::with('persona.documento')->get();
        return view('proveedores.index',compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        $documentos = Documento::all();
        return view('proveedores.create',compact('documentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonaRequest $request) : RedirectResponse
    {
        try {
            DB::beginTransaction();
            $persona = Persona::create($request->validated());
            $persona->proveedore()->create([

                'persona_id' => $persona->id
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        return redirect()->route('proveedores.index')->with('success','Proveedor Registrado');
    
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
    public function edit(Proveedore $proveedore): View
    {
        $proveedore->load('persona.documento');
        $documentos = Documento::all();
        return view('proveedores.edit', compact('proveedore','documentos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProveedorRequest $request, Proveedore $proveedore) : RedirectResponse
    {
        try{
            DB::beginTransaction();
            Persona::where('id',$proveedore->persona->id)
            ->Update($request->validated());

            DB::commit();

        }catch(Exception $e){
        DB::rollback();
        }
        return redirect()->route('proveedores.index')->with('success','Proveedor editado');  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $message = '';
        $persona = Persona::find($id);
        if ($persona->estado == 1) {
            Persona::where('id', $persona->id)
                ->update([
                    'estado' => 0
                ]);
            $message = 'Proveedor Eliminado';
        } else {
            Persona::where('id', $persona->id)
                ->update([
                    'estado' => 1
                ]);
            $message = 'Proveedor restaurado';
        }

        return redirect()->route('proveedores.index')->with('success', $message);
    }
}
