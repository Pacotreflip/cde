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
Route::get("areas-tipo/{id}/articulos_requeridos_xls", "AreasTipoController@articulosRequeridosXLS")->name('areas-tipo.articulos_requeridos_xls');
Route::get('areas-tipo', 'AreasTipoController@index')->name('tipos.index');
Route::get('areas-tipo/nuevo', 'AreasTipoController@create')->name('tipos.create');
Route::post('areas-tipo', 'AreasTipoController@store')->name('tipos.store');
Route::get('areas-tipo/{id}/modificar', 'AreasTipoController@edit')->name('tipos.edit');
Route::patch('areas-tipo/{id}', 'AreasTipoController@update')->name('tipos.update');
Route::delete('areas-tipo/{id}', 'AreasTipoController@destroy')->name('tipos.delete');
Route::post('areas-tipo/{id}/actualiza_areas', 'AreasTipoController@actualizaAreas')->name('tipos.actualiza_areas');


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
    Route::post('comparativa', 'AreasTipoComparativaController@consultaFiltrada')->name("comparativa.consulta");
    
    Route::get('articulos-requeridos-desde-area/seleccion-area', 'ArticulosRequeridosDesdeAreaController@create')->name('requerimientos.area.seleccion');
    Route::post('articulos-requeridos-desde-area/seleccion-area', 'ArticulosRequeridosDesdeAreaController@muestraMateriales')->name('requerimientos.articulos.area.seleccionada');
    Route::post('articulos-requeridos-desde-area/', 'ArticulosRequeridosDesdeAreaController@store')->name('requerimientos.copia.desde.area.store');
});

// Rutas de areas...
Route::get("areas/{id}/articulos_requeridos_xls", "AreasController@articulosRequeridosXLS")->name('areas.articulos_requeridos_xls');
Route::get('areas/areas-jstree', 'AreasController@areasJs')->name("areas.areasJs");
Route::get('areas_tipo/{id}/areas-jstree', 'AreasTipoController@areasJs')->name("areas_tipo.areasJs");
Route::get('areas', 'AreasController@index')->name('areas.index');
Route::get('areas/nueva', 'AreasController@create')->name('areas.create');
Route::post('areas', 'AreasController@store')->name('areas.store');
Route::get('areas/{id}', 'AreasController@edit')->name('areas.edit');
Route::patch('areas/{id}', 'AreasController@update')->name('areas.update');
Route::patch('areas/{id}/down', 'AreasController@down')->name('areas.down');
Route::patch('areas/{id}/up', 'AreasController@up')->name('areas.up');
Route::patch('areas/{id}/concepto', 'AreasController@generaConceptoSAO')->name('areas.genera.concepto.sao');
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
Route::delete('articulos/{id_material}/ficha_tecnica/delete', 'ArticulosController@elimina_ficha')->name('articulos.ficha_tecnica.delete');

// Rutas de proveedores...
Route::get('proveedores', 'ProveedoresController@index')->name('proveedores.index');
Route::get('proveedores/nuevo', 'ProveedoresController@create')->name('proveedores.create');
Route::post('proveedores', 'ProveedoresController@store')->name('proveedores.store');
Route::get('proveedores/{id}', 'ProveedoresController@edit')->name('proveedores.edit');
Route::patch('proveedores/{id}', 'ProveedoresController@update')->name('proveedores.update');
Route::delete('proveedores/{id}', 'ProveedoresController@destroy')->name('proveedores.delete');

// Rutas de compras...
Route::get("compras/xls", "ComprasController@comprasXLS")->name('compras.xls');
Route::get('compras', 'ComprasController@index')->name('compras.index');
Route::get('compras/material/{id_material}', 'ComprasController@index_x_material')->name('compras.index_x_material');
Route::get('compras/{id}', 'ComprasController@show')->name('compras.show');

// Rutas de recepcion de articulos...
Route::get('recepciones', 'RecepcionesController@index')->name('recepciones.index');
Route::get('recepciones/recibir', 'RecepcionesController@create')->name('recepciones.create');
Route::post('recepciones', 'RecepcionesController@store')->name('recepciones.store');
Route::get('recepciones/{id}', 'RecepcionesController@show')->name('recepciones.show');
Route::patch('recepciones/{id}', 'RecepcionesController@update')->name('recepciones.update');
Route::post('recepciones/{id}/comprobantes', 'ComprobantesController@store')->name('recepciones.comprobantes');
Route::delete('recepciones/{id_recepcion}/comprobantes/{id}', 'ComprobantesController@destroy')->name('recepciones.comprobantes.delete');
Route::post('recepciones/{id}', 'RecepcionesController@destroy')->name('recepciones.delete');

// Rutas de transferencias...
Route::get('transferencias', 'TransferenciasController@index')->name('transferencias.index');
Route::get('transferencias/transferir', 'TransferenciasController@create')->name('transferencias.create');
Route::post('transferencias', 'TransferenciasController@store')->name('transferencias.store');
Route::get('transferencias/{id}', 'TransferenciasController@show')->name('transferencias.show');
Route::post('transferencias/{id}', 'TransferenciasController@destroy')->name('transferencias.delete');
Route::get('transferencia/materiales', 'TransferenciasController@getMateriales')->name('transferir.materiales');
Route::post('transferir/filtrar/', 'TransferenciasController@filtrar')->name('transferir.filtrar');


// Rutas de asignaciones...
Route::get('asignaciones', 'AsignacionesController@index')->name('asignaciones.index');
Route::get('asignaciones/{id}', 'AsignacionesController@show')->name('asignaciones.show');
Route::post('asignaciones', 'AsignacionesController@store')->name('asignaciones.store');
Route::post('asignaciones/{id}', 'AsignacionesController@destroy')->name('asignaciones.delete');
Route::get('asignar/inventarios', 'AsignacionesController@create')->name('asignar.create');
Route::get('asignar/inventarios/{id}', 'AsignacionesController@create')->name('asignar.areacreate');
Route::get('asignar/destinos/{id_articulo}', 'AsignacionesController@getDestinos')->name('asignar.destinos');
Route::get('asignar/destino/{id_articulo}/{id_destino}', 'AsignacionesController@getDestino')->name('asignar.destino');
Route::get('asignar/material/{id_area}/{id_articulo}', 'AsignacionesController@getMaterial')->name('asignar.material');
Route::get('asignar/materiales', 'AsignacionesController@getMateriales')->name('asignar.materiales');
Route::post('asignar/filtrar/', 'AsignacionesController@filtrar')->name('asignar.filtrar');

// Rutas del api...
Route::group(['prefix' => 'api'], function () {
    Route::get('areas', 'Api\AreasController@index');
    Route::get('areas/{id}', 'Api\AreasController@show')
        ->where(['id' => '[0-9]+']);
    Route::get('areas/jstree', 'Api\AreasJsTreeController@areas');
    Route::get('areas/{id}/children/jstree', 'Api\AreasJsTreeController@areas')
        ->where(['id' => '[0-9]+']);
    Route::get('areas/jstree?id={id}', 'Api\AreasJsTreeController@areas')
        ->where(['id' => '[0-9]+']);
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

Route::group(["middleware" => ['permission:cierre_area']], function () {
    
    Route::post("/cierres/validar_asignaciones", ["as" => "cierre.validar.asignaciones", "uses" => "CierresController@validarAsignaciones"]);
    Route::post("/cierres/validar_todas_asignaciones/{id}", ["as" => "cierre.validar.todas.asignaciones.area", "uses" => "CierresController@validarTodasAsignaciones"]);
    Route::post("/cierres/busqueda/areas", ["as" => "cierre.busqueda.areas", "uses" => "CierresController@getFormularioBusquedaAreas"]);
    Route::get("/cierres/validar_asignaciones/area/{id}", ["as" => "cierre.valida.asignaciones.area", "uses" => "CierresController@getFormularioValidacionArea"]);
    Route::post("/cierres/regresa/areas/{parametro}", ["as" => "cierre.get.areas", "uses" => "CierresController@getAreas"]);
    Route::post("/cierres/carga/areas/", ["as" => "cierre.carga.areas", "uses" => "CierresController@getAreasSeleccionadas"]);
    Route::post("/cierres/create", ["as" => "cierre.create.areas", "uses" => "CierresController@getAreasSeleccionadas"]);
    Route::post('cierres/{id}', 'CierresController@destroy')->name('cierres.delete');
    Route::resource('cierres', 'CierresController', ['names' => [
            'index' => 'cierres.index',
            'create' => 'cierres.create',
            'store' => 'cierres.store',
            'show' => 'cierres.show',
            'edit' => 'cierres.edit',
            'update' => 'cierres.update',
            'destroy' => 'cierres.destroy'
    ]]);
});

Route::group(["middleware" => ['permission:entrega_area']], function () {
    Route::post("/entregas/validar_asignaciones", ["as" => "entrega.validar.asignaciones", "uses" => "EntregasController@validarAsignaciones"]);
    Route::post("/entregas/validar_todas_asignaciones/{id}", ["as" => "entrega.validar.todas.asignaciones.area", "uses" => "EntregasController@validarTodasAsignaciones"]);
    Route::post("/entregas/busqueda/areas", ["as" => "entrega.busqueda.areas", "uses" => "EntregasController@getFormularioBusquedaAreas"]);
    Route::get("/entregas/validar_asignaciones/area/{id}", ["as" => "entrega.valida.asignaciones.area", "uses" => "EntregasController@getFormularioValidacionArea"]);
    Route::post("/entregas/regresa/areas/{parametro}", ["as" => "entrega.get.areas", "uses" => "EntregasController@getAreas"]);
    Route::post("/entregas/carga/areas/", ["as" => "entrega.carga.areas", "uses" => "EntregasController@getAreasSeleccionadas"]);
    Route::post("/entregas/create", ["as" => "entrega.create.areas", "uses" => "EntregasController@getAreasSeleccionadas"]);
    Route::post('entregas/{id}', 'EntregasController@destroy')->name('entregas.delete');
    Route::resource('entregas', 'EntregasController', ['names' => [
            'index' => 'entregas.index',
            'create' => 'entregas.create',
            'store' => 'entregas.store',
            'show' => 'entregas.show',
            'edit' => 'entregas.edit',
            'update' => 'entregas.update',
            'destroy' => 'entregas.destroy'
    ]]);
});

//PDF Routes
Route::get('PDF/recepciones/{id}', 'PDFController@recepciones')->name('pdf.recepciones');
Route::get('PDF/transferencias/{id}', 'PDFController@transferencias')->name('pdf.transferencias');
Route::get('PDF/compras/{id}', 'PDFController@compras')->name('pdf.compras');
Route::get('PDF/asignaciones/{id}', 'PDFController@asignaciones')->name('pdf.asignaciones');
Route::get('PDF/cierres/{id}', 'PDFController@cierres')->name('pdf.cierres');
Route::get('PDF/entregas/{id}', 'PDFController@entregas')->name('pdf.entregas');

Route::get('reportes/comparativa', 'ReportesController@index_comparativa')->name('reportes.comparativa');
Route::post("reportes/comparativa/descarga_excel", "ReportesController@comparativaDescargaExcel")->name('reportes.comparativa_xls');
Route::get('reportes/estatus_desarrollo', 'ReportesController@index_estatus_desarrollo')->name('reportes.estatus_desarrollo');
Route::post('reportes/comparativa_equipamiento', 'ReportesController@index_reporte_comparativa')->name('reportes.comparativa_equipamiento');
Route::post('reportes/comparativa_equipamiento/resultado', 'ReportesController@recargaResultado')->name('reportes.tabla_resultado_comparativa_equipamiento');
Route::get('reportes/comparativa_equipamiento', 'ReportesController@index_reporte_comparativa')->name('reportes.comparativa_equipamiento');
Route::post("reportes/comparativa_equipamiento/resultado/descarga_excel", "ReportesController@descargaExcel")->name('reportes.comparativa_equipamiento_xls');
Route::get('reportes/materiales_orden_compra', 'ReportesController@index_reporte_materiales_oc')->name('reportes.materiales_ordenes_compra');
Route::post("reportes/materiales_orden_compra/resultado/descarga_excel", "ReportesController@materialesOCDescargaExcel")->name('reportes.materiales_ordenes_compra_xls');
Route::get('reportes/materiales_oc_vs_materiales_req', 'ReportesController@index_reporte_materiales_oc_vs_materiales_req')->name('reportes.materiales_oc_vs_materiales_req');
Route::post("reportes/materiales_oc_vs_materiales_req/resultado/descarga_excel", "ReportesController@materialesOCVSREQDescargaExcel")->name('reportes.materiales_oc_vs_materiales_req_xls');

