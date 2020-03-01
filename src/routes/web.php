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

Route::get('/documentacao', 'DocController@view')->name('doc');
Auth::routes();
Route::group(['middleware' => 'auth'], function() {
	Route::get('/', function () {
	    return redirect()->route('admin.cliente.listView');
	});	
	/*
	Retorna view que lista de todos os usuários
	 */ 
	Route::get('/admin', 'ClienteController@view')->name('admin.cliente.listView');
	Route::get('/admin/cliente', 'ClienteController@view')->name('admin.cliente.listView');
	/*
	Retorna view de registro.
	 */
	Route::get('/admin/cliente/register', 'ClienteController@registerView')->name('admin.cliente.registerView');
	/*
	Retorna view de alterar cliente. Dados do cliente passados por POST.
	 */
	Route::post('/admin/cliente/register/{id}', 'ClienteController@registerView')->name('admin.cliente.alteraView');

	/*
	Rotas que usam os métodos da API internamente
	 */
	Route::post('/admin/cliente/register', 'ClienteController@clientCreate')->name('admin.cliente.register');
	Route::put('/admin/cliente/{id}', 'ClienteController@clientUpdate')->name('admin.cliente.alterar');
	Route::delete('/admin/cliente/{id}', 'ClienteController@clientDelete')->name('admin.cliente.delete');

	/*
	admin/users
	 */
	Route::get('/admin/user', 'UserController@view')->name('admin.user.listView');
	Route::get('/admin/user/register', 'UserController@register')->name('admin.user.register');
	Route::delete('/admin/user/{id}', 'UserController@delete')->name('admin.user.delete');

	/*
	Documentação
	 */
	Route::get('/admin/documentacao', 'DocController@adminView')->name('admin.doc');
});	
