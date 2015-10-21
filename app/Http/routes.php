<?php

// Rutas de paginas...
Route::get('/', ['as' => 'home', 'uses' => 'PagesController@home']);
Route::get('obras/', ['as' => 'obras', 'uses' => 'PagesController@obras']);

// Rutas de contexto...
Route::get('/context/{databaseName}/{id_obra}', ['as' => 'context.set', 'uses' => 'ContextController@set'])
    ->where(['databaseName' => '[aA-zZ0-9_-]+', 'id_obra' => '[0-9]+']);

// Rutas de autenticacion...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Rutas de tipos de area...
Route::get('tipos-area', ['as' => 'tipos.index', 'uses' => 'TiposAreaController@index']);
Route::get('tipos-area/nuevo', ['as' => 'tipos.create', 'uses' => 'TiposAreaController@create']);
Route::post('tipos-area', ['as' => 'tipos.store', 'uses' => 'TiposAreaController@store']);
Route::get('tipos-area/{id}/modificar', ['as' => 'tipos.edit', 'uses' => 'TiposAreaController@edit']);
Route::patch('tipos-area/{id}', ['as' => 'tipos.update', 'uses' => 'TiposAreaController@update']);
Route::delete('tipos-area/{id}', ['as' => 'tipos.delete', 'uses' => 'TiposAreaController@destroy']);

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
Route::post('articulos/{id}/fotos', ['as' => 'articulos.fotos', 'uses' => 'FotosController@store']);
Route::delete('articulos/{id_material}/fotos/{id}', ['as' => 'articulos.fotos.delete', 'uses' => 'FotosController@destroy']);

// Rutas de proveedores...
Route::get('proveedores', ['as' => 'proveedores.index', 'uses' => 'ProveedoresController@index']);
Route::get('proveedores/nuevo', ['as' => 'proveedores.create', 'uses' => 'ProveedoresController@create']);
Route::post('proveedores', ['as' => 'proveedores.store', 'uses' => 'ProveedoresController@store']);
Route::get('proveedores/{id}', ['as' => 'proveedores.edit', 'uses' => 'ProveedoresController@edit']);
Route::patch('proveedores/{id}', ['as' => 'proveedores.update', 'uses' => 'ProveedoresController@update']);
Route::delete('proveedores/{id}', ['as' => 'proveedores.delete', 'uses' => 'ProveedoresController@destroy']);

// Rutas de asignacion de requerimientos...
Route::group(['prefix' => 'tipos-area/{id}'], function () {
    Route::get('asignacion-requerimientos', ['as' => 'requerimientos.edit', 'uses' => 'AsignacionRequerimientosController@edit']);
    Route::get('asignacion-requerimientos/seleccion-articulos', ['as' => 'requerimientos.seleccion', 'uses' => 'AsignacionRequerimientosController@create']);
    Route::post('asignacion-requerimientos', ['as' => 'requerimientos.store', 'uses' => 'AsignacionRequerimientosController@store']);
    Route::patch('asignacion-requerimientos', ['as' => 'requerimientos.update', 'uses' => 'AsignacionRequerimientosController@update']);
});

// Rutas de compras...
Route::get('compras', ['as' => 'compras.index', 'uses' => 'ComprasController@index']);
Route::get('compras/{id}', ['as' => 'compras.show', 'uses' => 'ComprasController@show']);

// Rutas de recepcion de articulos...
Route::get('recepciones', ['as' => 'recepciones.index', 'uses' => 'RecepcionesController@index']);
Route::get('recepciones/recibir', ['as' => 'recepciones.create', 'uses' => 'RecepcionesController@create']);
Route::post('recepciones', ['as' => 'recepciones.store', 'uses' => 'RecepcionesController@store']);
Route::get('recepciones/{id}', ['as' => 'recepciones.show', 'uses' => 'RecepcionesController@show']);
Route::patch('recepciones/{id}', ['as' => 'recepciones.update', 'uses' => 'RecepcionesController@update']);
Route::delete('recepciones/{id}', ['as' => 'recepciones.delete', 'uses' => 'RecepcionesController@destroy']);

// Rutas de transferencias...
Route::get('transferencias', ['as' => 'transferencias.index', 'uses' => 'TransferenciasController@index']);
Route::get('transferencias/transferir', ['as' => 'transferencias.create', 'uses' => 'TransferenciasController@create']);
Route::post('transferencias', ['as' => 'transferencias.store', 'uses' => 'TransferenciasController@store']);
Route::get('transferencias/{id}', ['as' => 'transferencias.show', 'uses' => 'TransferenciasController@show']);
Route::delete('transferencias/{id}', ['as' => 'transferencias.delete', 'uses' => 'TransferenciasController@destroy']);

// Rutas de asignaciones...
Route::get('asignaciones', ['as' => 'asignaciones.index', 'uses' => 'AsignacionesController@index']);
Route::get('asignaciones/asignar', ['as' => 'asignaciones.create', 'uses' => 'AsignacionesController@create']);
Route::post('asignaciones', ['as' => 'asignaciones.store', 'uses' => 'AsignacionesController@store']);
Route::get('asignaciones/{id}', ['as' => 'asignaciones.show', 'uses' => 'AsignacionesController@show']);

// Rutas del api...
Route::group(['prefix' => 'api'], function () {
    Route::get('areas', 'Api\AreasController@index');
    Route::get('areas/{id}', 'Api\AreasController@show')->where(['id' => '[0-9]+']);
    Route::get('areas/jstree', 'Api\AreasJsTreeController@areas');
    Route::get('areas/{id}/children/jstree', 'Api\AreasJsTreeController@areas')->where(['id' => '[0-9]+']);;
    Route::get('materiales', 'Api\MaterialesController@index');
    Route::get('ordenes-compra/{id}', 'Api\OrdenesCompraController@show');
});
