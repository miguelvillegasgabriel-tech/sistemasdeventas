<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB; // Importar el facade DB para usar transacciones


class roleController extends Controller implements HasMiddleware
{

      public static function middleware(): array {
        return [ 
          new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('ver-role|crear-role|editar-role|eliminar-role'),only:['index']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('crear-role'), only:['create','store']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('editar-role'),only:['edit','update']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('eliminar-role'), only:['destroy']),
        ];
     }

   

    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $roles = Role::all();
        return view('role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        $permisos = Permission::all();
        return view('role.create', compact('permisos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array|min:1',

        ]);

        try {
            DB::beginTransaction();
             $rol = Role::create(['name' => $request->name]);

            $rol->syncPermissions($request->permission);
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
        }

       
        return redirect()->route('roles.index')->with('success', 'Rol registrado');
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
    public function edit(Role $role): View
    {
            $permisos = Permission::all();
        return view('role.edit', compact('role','permisos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
            'permission' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $rol = Role::where('id',$role->id)
            ->update([
                'name' => $request->name
            ]);

            $role->syncPermissions($request->permission);
         


            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('roles.index')->with('success', 'rol editado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        Role::where('id', $id)->delete();
        return redirect()->route('roles.index')->with('success', 'rol eliminado');
    }
}
