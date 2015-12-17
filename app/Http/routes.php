<?php

// Rutas de paginas...
Route::get('/', 'PagesController@home')->name('home');
Route::get('obras/', 'PagesController@obras')->name('obras');

// Rutas de contexto...
Route::get('/context/{databaseName}/{id_obra}', 'ContextController@set')
    ->name('context.set')
    ->where(['databaseName' => '[aA-zZ0-9_-]+', 'id_obra' => '[0-9]+']);

// Rutas de autenticacion...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Rutas de areas tipo...
Route::get('areas-tipo', 'AreasTipoController@index')->name('tipos.index');
Route::get('areas-tipo/nuevo', 'AreasTipoController@create')->name('tipos.create');
Route::post('areas-tipo', 'AreasTipoController@store')->name('tipos.store');
Route::get('areas-tipo/{id}/modificar', 'AreasTipoController@edit')->name('tipos.edit');
Route::patch('areas-tipo/{id}', 'AreasTipoController@update')->name('tipos.update');
Route::delete('areas-tipo/{id}', 'AreasTipoController@destroy')->name('tipos.delete');

// Subrutas de area tipo...
Route::group(['prefix' => 'areas-tipo/{id}', 'namespace' => 'AreasTipo'], function () {
    // Rutas de articulos requeridos...
    Route::get('articulos-requeridos', 'ArticulosRequeridosController@edit')->name('requerimientos.edit');
    Route::get('articulos-requeridos/seleccion-articulos', 'ArticulosRequeridosController@create')->name('requerimientos.seleccion');
    Route::post('articulos-requeridos', 'ArticulosRequeridosController@store')->name('requerimientos.store');
    Route::patch('articulos-requeridos', 'ArticulosRequeridosController@update')->name('requerimientos.update');
    // Ruta de areas asignadas...
    Route::get('areas-asignadas', 'AreasAsignadasController@index');
    // Rutas de evaluacion de calidad...
    Route::get('evaluacion-calidad', 'EvaluacionCalidadController@index');
    Route::patch('evaluacion-calidad', 'EvaluacionCalidadController@update');
    // Ruta de comparativa...
    Route::get('comparativa', 'AreasTipoComparativaController@index');
});

// Rutas de areas...
Route::get('areas', 'AreasController@index')->name('areas.index');
Route::get('areas/nueva', 'AreasController@create')->name('areas.create');
Route::post('areas', 'AreasController@store')->name('areas.store');
Route::get('areas/{id}', 'AreasController@edit')->name('areas.edit');
Route::patch('areas/{id}', 'AreasController@update')->name('areas.update');
Route::delete('areas/{id}', 'AreasController@destroy')->name('areas.delete');

// Rutas de clasificadores de articulos...
Route::get('clasificadores-articulo', 'ClasificadoresController@index')->name('clasificadores.index');
Route::get('clasificadores-articulo/nuevo', 'ClasificadoresController@create')->name('clasificadores.create');
Route::post('clasificadores-articulo', 'ClasificadoresController@store')->name('clasificadores.store');
Route::get('clasificadores-articulo/{id}', 'ClasificadoresController@edit')->name('clasificadores.edit');
Route::patch('clasificadores-articulo/{id}', 'ClasificadoresController@update')->name('clasificadores.update');
Route::delete('clasificadores-articulo/{id}', 'ClasificadoresController@destroy')->name('clasificadores.delete');

// Rutas de articulos...
Route::get('articulos', 'ArticulosController@index')->name('articulos.index');
Route::get('articulos/nuevo', 'ArticulosController@create')->name('articulos.create');
Route::post('articulos', 'ArticulosController@store')->name('articulos.store');
Route::get('articulos/{id}', 'ArticulosController@edit')->name('articulos.edit');
Route::patch('articulos/{id}', 'ArticulosController@update')->name('articulos.update');
Route::post('articulos/{id}/fotos', 'FotosController@store')->name('articulos.fotos');
Route::delete('articulos/{id_material}/fotos/{id}', 'FotosController@destroy')->name('articulos.fotos.delete');

// Rutas de proveedores...
Route::get('proveedores', 'ProveedoresController@index')->name('proveedores.index');
Route::get('proveedores/nuevo', 'ProveedoresController@create')->name('proveedores.create');
Route::post('proveedores', 'ProveedoresController@store')->name('proveedores.store');
Route::get('proveedores/{id}', 'ProveedoresController@edit')->name('proveedores.edit');
Route::patch('proveedores/{id}', 'ProveedoresController@update')->name('proveedores.update');
Route::delete('proveedores/{id}', 'ProveedoresController@destroy')->name('proveedores.delete');

// Rutas de compras...
Route::get('compras', 'ComprasController@index')->name('compras.index');
Route::get('compras/{id}', 'ComprasController@show')->name('compras.show');

// Rutas de recepcion de articulos...
Route::get('recepciones', 'RecepcionesController@index')->name('recepciones.index');
Route::get('recepciones/recibir', 'RecepcionesController@create')->name('recepciones.create');
Route::post('recepciones', 'RecepcionesController@store')->name('recepciones.store');
Route::get('recepciones/{id}', 'RecepcionesController@show')->name('recepciones.show');
Route::patch('recepciones/{id}', 'RecepcionesController@update')->name('recepciones.update');
Route::delete('recepciones/{id}', 'RecepcionesController@destroy')->name('recepciones.delete');

// Rutas de transferencias...
Route::get('transferencias', 'TransferenciasController@index')->name('transferencias.index');
Route::get('transferencias/transferir', 'TransferenciasController@create')->name('transferencias.create');
Route::post('transferencias', 'TransferenciasController@store')->name('transferencias.store');
Route::get('transferencias/{id}', 'TransferenciasController@show')->name('transferencias.show');
Route::delete('transferencias/{id}', 'TransferenciasController@destroy')->name('transferencias.delete');

// Rutas de asignaciones...
Route::get('asignaciones', 'AsignacionesController@index')->name('asignaciones.index');
Route::get('asignaciones/asignar', 'AsignacionesController@create')->name('asignaciones.create');
Route::post('asignaciones', 'AsignacionesController@store')->name('asignaciones.store');
Route::get('asignaciones/{id}', 'AsignacionesController@show')->name('asignaciones.show');

// Rutas del api...
Route::group(['prefix' => 'api'], function () {
    Route::get('areas', 'Api\AreasController@index');
    Route::get('areas/{id}', 'Api\AreasController@show')
        ->where(['id' => '[0-9]+']);
    Route::get('areas/jstree', 'Api\AreasJsTreeController@areas');
    Route::get('areas/{id}/children/jstree', 'Api\AreasJsTreeController@areas')
        ->where(['id' => '[0-9]+']);;
    Route::get('materiales', 'Api\MaterialesController@index');
    Route::get('ordenes-compra/{id}', 'Api\OrdenesCompraController@show');
    Route::get('areas-tipo/{id}/comparativa', 'AreasTipo\AreasTipoComparativaController@comparativa');
});


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
