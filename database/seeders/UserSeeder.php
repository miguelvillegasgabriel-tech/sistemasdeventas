<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $user = User::create([
            
        //     'name' => 'Beto Neo',
        //     'email' => 'betoneo@gmail.com',
        //     'password' => bcrypt('12345678')
            
        // ]);

        //usuario administrador
         $rol = Role::firstOrCreate(
            ['name' => 'administrador', 'guard_name' => 'web']
        );
        $permisos = Permission::pluck('id','id')->all();
        $rol->syncPermissions($permisos);
        $user = User::find(1);
        $user->assignRole('administrador');
    }
}
