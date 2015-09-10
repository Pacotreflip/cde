<?php

// Rutas de paginas...
Route::get('/', ['as' => 'home', 'uses' => 'PagesController@home']);
Route::get('obras/', ['as' => 'obras', 'uses' => 'PagesController@obras']);

// Rutas de contexto
Route::get('/context/{databaseName}/{id_obra}', ['as' => 'context.set', 'uses' => 'ContextController@set'])
    ->where(['databaseName' => '[aA-zZ0-9_-]+', 'id_obra' => '[0-9]+']);

// Rutas de autenticacion...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Rutas de tipos de area...
Route::get('tipos', ['as' => 'tipos.index', 'uses' => 'TiposAreaController@index']);
Route::get('tipos/nuevo', ['as' => 'tipos.create', 'uses' => 'TiposAreaController@create']);
Route::post('tipos', ['as' => 'tipos.store', 'uses' => 'TiposAreaController@store']);
Route::get('tipos/{id}/modificar', ['as' => 'tipos.edit', 'uses' => 'TiposAreaController@edit']);
Route::patch('tipos/{id}', ['as' => 'tipos.update', 'uses' => 'TiposAreaController@update']);
Route::delete('tipos/{id}', ['as' => 'tipos.delete', 'uses' => 'TiposAreaController@destroy']);

// Rutas de areas...
Route::get('areas', ['as' => 'areas.index', 'uses' => 'AreasController@index']);
Route::get('areas/nueva', ['as' => 'areas.create', 'uses' => 'AreasController@create']);
Route::post('areas', ['as' => 'areas.store', 'uses' => 'AreasController@store']);
Route::get('areas/{id}', ['as' => 'areas.edit', 'uses' => 'AreasController@edit']);
Route::patch('areas/{id}', ['as' => 'areas.update', 'uses' => 'AreasController@update']);
Route::delete('areas/{id}', ['as' => 'areas.delete', 'uses' => 'AreasController@destroy']);

// Rutas de clasificadores de articulos...
Route::get('clasificadores-articulo', ['as' => 'clasificadores.index', 'uses' => 'ClasificadoresController@index']);
Route::get('clasificadores-articulo/nuevo', ['as' => 'clasificadores.create', 'uses' => 'ClasificadoresController@create']);
Route::post('clasificadores-articulo', ['as' => 'clasificadores.store', 'uses' => 'ClasificadoresController@store']);
Route::get('clasificadores-articulo/{id}', ['as' => 'clasificadores.edit', 'uses' => 'ClasificadoresController@edit']);
Route::patch('clasificadores-articulo/{id}', ['as' => 'clasificadores.update', 'uses' => 'ClasificadoresController@update']);
Route::delete('clasificadores-articulo/{id}', ['as' => 'clasificadores.delete', 'uses' => 'ClasificadoresController@destroy']);

// Rutas de articulos...
Route::get('articulos', ['as' => 'articulos.index', 'uses' => 'ArticulosController@index']);
Route::get('articulos/nuevo', ['as' => 'articulos.create', 'uses' => 'ArticulosController@create']);
Route::post('articulos', ['as' => 'articulos.store', 'uses' => 'ArticulosController@store']);
Route::get('articulos/{id}', ['as' => 'articulos.edit', 'uses' => 'ArticulosController@edit']);
Route::patch('articulos/{id}', ['as' => 'articulos.update', 'uses' => 'ArticulosController@update']);
Route::post('articulos/{id}/fotos', ['as' => 'articulos.fotos', 'uses' => 'ArticulosController@agregaFoto']);

// Rutas de proveedores...
Route::get('proveedores', ['as' => 'proveedores.index', 'uses' => 'ProveedoresController@index']);
Route::get('proveedores/nuevo', ['as' => 'proveedores.create', 'uses' => 'ProveedoresController@create']);
Route::post('proveedores', ['as' => 'proveedores.store', 'uses' => 'ProveedoresController@store']);
Route::get('proveedores/{id}', ['as' => 'proveedores.edit', 'uses' => 'ProveedoresController@edit']);
Route::patch('proveedores/{id}', ['as' => 'proveedores.update', 'uses' => 'ProveedoresController@update']);
Route::delete('proveedores/{id}', ['as' => 'proveedores.delete', 'uses' => 'ProveedoresController@destroy']);
