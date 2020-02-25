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

Route::get('/', function () {
    return view('clientes');
});

/*
 * Rotas de view
 */
Route::get('/admin', 'ClienteController@clienteView')->name('admin.cliente.listView');
Route::get('/admin/cliente', 'ClienteController@clienteView')->name('admin.cliente.listView');
Route::get('/admin/cliente/register', 'ClienteController@clienteRegisterView')->name('admin.cliente.registerView');
Route::post('/admin/cliente/register/{id}', 'ClienteController@clienteRegisterView')->name('admin.cliente.alteraView');

/*
 * Rotas da API
 * CRUD de cliente
 */
Route::post('/admin/cliente/register', 'ClienteController@clienteCreate')->name('admin.cliente.register');
Route::put('/admin/cliente/alterar/{id}', 'ClienteController@clienteUpdate')->name('admin.cliente.alterar');
Route::delete('/admin/cliente/delete/{id}', 'ClienteController@clienteDelete')->name('admin.cliente.delete');