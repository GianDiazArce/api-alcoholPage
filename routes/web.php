<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::get('/', function () {
    return view('welcome');
});
*/
// Cargando clases
use App\Http\Middleware\ApiAuthMiddleware;

Route::get('/', 'PruebasController@index')->name('prueba-index');


// Rutas de category 
Route::get('/category','CategoryController@index')->name('categoria-index');
Route::post('/category/create', 'CategoryController@create')->name('crear-categoria');
Route::put('/category/{id}', 'CategoryController@update')->name('actualizar-categoria');
Route::delete('/category/{id}','CategoryController@delete')->name('borrar-categoria');

// Rutas de productos
Route::get('/product','ProductController@index')->name('productos-index');
Route::post('/product','ProductController@create')->name('crear-producto');
Route::post('/product/upload','ProductController@upload')->name('producto-subirImagen');
Route::get('/product/image/{filename}','ProductController@getImage');

//Route::resource('user', 'UserController');

// Rutas de Orders
Route::get('/order','OrderController@index')->name('order-name');
Route::post('/order','OrderController@create');
Route::get('/order/{id}','OrderController@findOrder');


// Rutas de usuario usando JWT Auth
Route::post('/login','UserController@login');
Route::post('/register', 'UserController@register');
Route::put('/user/update', 'UserController@update');
Route::post('/user/upload', 'UserController@upload')->middleware(ApiAuthMiddleware::class);
Route::get('/user/{id}','UserController@delete')->middleware(ApiAuthMiddleware::class);;