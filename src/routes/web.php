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
// Retorna view que lista de todos os usuários
Route::get('/admin', 'ClienteController@clientView')->name('admin.cliente.listView');
Route::get('/admin/cliente', 'ClienteController@clientView')->name('admin.cliente.listView');
// Retorna view de registro.
Route::get('/admin/cliente/register', 'ClienteController@clientRegisterView')->name('admin.cliente.registerView');
// Retorna view de alterar cliente. Dados do cliente passados por POST.
Route::post('/admin/cliente/register/{id}', 'ClienteController@clientRegisterView')->name('admin.cliente.alteraView');

/*
 * Rotas que usam os métodos da API internamente
 */
Route::post('/admin/cliente/register', 'ClienteController@clientCreate')->name('admin.cliente.register');
Route::put('/admin/cliente/alterar/{id}', 'ClienteController@clientUpdate')->name('admin.cliente.alterar');
Route::delete('/admin/cliente/delete/{id}', 'ClienteController@clientDelete')->name('admin.cliente.delete');