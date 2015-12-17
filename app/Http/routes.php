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


Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function() {
//    Route::get('/', 'AdminController@welcome');
//    Route::get('/manage', ['middleware' => ['permission:manage-admins'], 'uses' => 'AdminController@manageAdmins']);
//    
    Route::resource('roles', 'Auth\RoleController', ['names' => [
            'index' => 'roles_index_path',
            'create' => 'roles_create_path',
            'store' => 'roles_store_path',
            'show' => 'roles_show_path',
            'edit' => 'roles_edit_path',
            'update' => 'roles_update_path',
            'destroy' => 'roles_destroy_path'
    ]]);
    
    Route::resource('users', 'Auth\UserController', ['names' => [
            'index' => 'users_index_path',
            'create' => 'users_create_path',
            'store' => 'users_store_path',
            'show' => 'users_show_path',
            'edit' => 'users_edit_path',
            'update' => 'users_update_path',
            'destroy' => 'users_destroy_path'
    ]]);
    
    Route::resource('permissions', 'Auth\PermissionController', ['names' => [
            'index' => 'permissions_index_path',
            'create' => 'permissions_create_path',
            'store' => 'permissions_store_path',
            'show' => 'permissions_show_path',
            'edit' => 'permissions_edit_path',
            'update' => 'permissions_update_path',
            'destroy' => 'permissions_destroy_path'
    ]]);
    Route::get("/assign_permissions/{id}", ["as" => "assign_permissions_to_role_create_path", "uses" => "Auth\RoleController@getFormPermissions"]);
    Route::get("/assign_permissions/{id}", ["as" => "assign_permissions_to_role_create_path", "uses" => "Auth\RoleController@getFormPermissions"]);
    Route::post("/assign_permissions/remove", ["as" => "remove_permissions_to_role_store_path", "uses" => "Auth\RoleController@removePermissions"]);
    Route::post("/assign_permissions/assign", ["as" => "assign_permissions_to_role_store_path", "uses" => "Auth\RoleController@assignPermissions"]);
    Route::get("/users/role/{id}", ["as" => "role_to_user_show_path", "uses" => "Auth\UserController@getFormRole"]);
    Route::post("/users/assign_role/remove", ["as" => "remove_role_to_user_store_path", "uses" => "Auth\UserController@removeRole"]);
    Route::post("/users/assign_role/assign", ["as" => "assign_role_to_user_store_path", "uses" => "Auth\UserController@assignRole"]);
});
Route::group(["middleware" => ['role:admin']], function () {
    Route::get("/users/getList", ["as" => "usuarios_get_lista", "uses" => "Auth\UserController@getLista"]);//role_to_user_show_path
    
});
// Rutas de tipos de area...
Route::get('areas-tipo', ['as' => 'tipos.index', 'uses' => 'AreasTipoController@index']);
Route::get('areas-tipo/nuevo', ['as' => 'tipos.create', 'uses' => 'AreasTipoController@create']);
Route::post('areas-tipo', ['as' => 'tipos.store', 'uses' => 'AreasTipoController@store']);
Route::get('areas-tipo/{id}/modificar', ['as' => 'tipos.edit', 'uses' => 'AreasTipoController@edit']);
Route::patch('areas-tipo/{id}', ['as' => 'tipos.update', 'uses' => 'AreasTipoController@update']);
Route::delete('areas-tipo/{id}', ['as' => 'tipos.delete', 'uses' => 'AreasTipoController@destroy']);

// Rutas de asignacion de requerimientos...
Route::group(['prefix' => 'areas-tipo/{id}'], function () {
    Route::get('articulos-requeridos', ['as' => 'requerimientos.edit', 'uses' => 'ArticulosRequeridosController@edit']);
    Route::get('articulos-requeridos/seleccion-articulos', ['as' => 'requerimientos.seleccion', 'uses' => 'ArticulosRequeridosController@create']);
    Route::post('articulos-requeridos', ['as' => 'requerimientos.store', 'uses' => 'ArticulosRequeridosController@store']);
    Route::patch('articulos-requeridos', ['as' => 'requerimientos.update', 'uses' => 'ArticulosRequeridosController@update']);

    Route::get('areas-asignadas', 'AreasAsignadasController@index');
    Route::get('evaluacion-calidad', 'EvaluacionCalidadController@index');
    Route::patch('evaluacion-calidad', 'EvaluacionCalidadController@update');
    Route::get('comparativa', 'AreasTipoComparativaController@index');
});

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

    Route::get('areas-tipo/{id}/comparativa', 'AreasTipoComparativaController@comparativa');
});
