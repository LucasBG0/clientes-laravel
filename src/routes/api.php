<?php

use Illuminate\Http\Request;
Use App\Cliente;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'Auth\RegisterController@register')->name('api.register');
Route::post('login', 'Auth\LoginController@login')->name('api.login');
Route::post('logout', 'Auth\LoginController@logout');

Route::group(['middleware' => 'auth:api'], function() { 
	Route::get('clientes', 'APIController@getAllClientes')->name('api.getAllClientes');
	Route::get('clientes/{id}', 'APIController@getCliente');
	Route::get('tags', 'APIController@getAllTags');
	Route::post('clientes', 'APIController@store');
	Route::put('clientes/{cliente}', 'APIController@update');
	Route::delete('clientes/{cliente}', 'APIController@delete');
});	