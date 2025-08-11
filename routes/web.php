<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\PresentacioneController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\compraController;
use App\Http\Controllers\ventaController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\userController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\profileController;


// Route::get('/', function () {
//     return view('template');
// });

Route::get('/',[homeController::class,'index'])->name('panel');
// Route::view('/panel', 'panel.index')->name('panel');





// Route::resources([
   
//     'marcas' => MarcaController::class,
//     'presentaciones' => PresentacioneController::class,
//     'productos' => ProductoController::class,
//     'clientes' => ClienteController::class,
//     'proveedores' => ProveedorController::class,
//     'compras' => compraController::class,
//     'ventas' => ventaController::class,
//     'users' => userController::class,
//     'roles' => roleController::class,
//     'profile' => profileController::class


// ]);

Route::resource('categorias',CategoriaController::class)->except('show');
Route::resource('presentaciones',PresentacioneController::class)->except('show');
Route::resource('marcas',MarcaController::class)->except('show');
Route::resource('productos',ProductoController::class)->except('show');
Route::resource('clientes',ClienteController::class)->except('show');
Route::resource('proveedores',ProveedorController::class)->except('show');
Route::resource('compras',compraController::class)->except('edit', 'update');
Route::resource('ventas',ventaController::class)->except('edit', 'update');
Route::resource('users',userController::class)->except('show');
Route::resource('roles',roleController::class)->except('show');
Route::resource('profile',profileController::class)->except('create', 'store', 'show', 'edit', 'destroy');

Route::get('/login',[loginController::class,'index'])->name('login');   
Route::post('/login',[loginController::class,'login'])->name('login.login'); 
Route::get('/logout',[logoutController::class,'logout'])->name('logout');
// Route::get('/login', function () {
//     return view('auth.login');
// });

Route::get('/401', function () {
    return view('pages.401');
});

Route::get('/404', function () {
    return view('pages.404');
});

Route::get('/500', function () {
    return view('pages.500');
});