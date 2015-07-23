<?php

/**
 * Paginas
 */
Route::get('/', [
    'as' => 'pages.home',
    'uses' => 'PagesController@home'
]);

Route::get('/obras', [
    'before' => 'auth',
    'as' => 'pages.obras',
    'uses' => 'PagesController@obras'
]);

Route::get('/context/{databaseName}/{idObra}', [
    'as' => 'context.set',
    'uses' => 'ContextController@set',
])
    ->where([
        'databaseName' => '[aA-zZ0-9_-]+',
        'idObra' => '[0-9]+'
    ]);


/**
 * Autenticacion
 */
Route::get('auth/login', [
    'as' => 'auth.login',
    'uses' => 'Auth\AuthController@getLogin'
]);

Route::post('auth/login', [
    'as' => 'auth.login',
    'uses' => 'Auth\AuthController@postLogin'
]);

Route::get('auth/logout', [
    'as' => 'auth.logout',
    'uses' => 'Auth\AuthController@getLogout'
]);

Route::group(['prefix' => 'api', 'as' => 'api.'], function()
{
////    \TenantContext::setConnectionName('SAO1814_DEVELOP');
////    \TenantContext::setTenantId(1);
////    if ( ! Auth::check())
////    {
////        \Auth::loginUsingId(1);
////    }
//
//    get('/context/{connectionName}/{idObra}', 'Api\ContextsController@store');
//
//    get('obras', 'Api\ObrasController@lists');
//
//    get('conceptos/{id}', 'Api\ConceptosController@show');
//
    Route::get('conceptos', 'Api\ConceptosController@lists');
    Route::get('conceptos/jstree', 'Api\ConceptosJsTreeController@getRoot');
    Route::get('conceptos/{id}/jstree', 'Api\ConceptosJsTreeController@getNode');
});


/**
 * Almacenes
 */
Route::get('almacenes', [
    'as' => 'almacenes.index',
    'uses' => 'Almacenes\AlmacenesController@index',
]);

Route::get('almacenes/nuevo', [
    'as' => 'almacenes.create',
    'uses' => 'Almacenes\AlmacenesController@create',
]);

Route::get('almacenes/{id}', [
    'as' => 'almacenes.show',
    'uses' => 'Almacenes\AlmacenesController@show',
])->where('id', '[0-9]+');

Route::post('almacenes', [
    'as' => 'almacenes.store',
    'uses' => 'Almacenes\AlmacenesController@store',
]);

Route::get('almacenes/{id}/modificar', [
    'as' => 'almacenes.edit',
    'uses' => 'Almacenes\AlmacenesController@edit',
]);

Route::patch('almacenes/{id}', [
    'as' => 'almacenes.update',
    'uses' => 'Almacenes\AlmacenesController@update',
]);

/**
 * Horas Mensuales
 */

Route::get('almacenes/{id_almacen}/horas-mensuales', [
    'as' => 'horas-mensuales.index',
    'uses' => 'Almacenes\HorasMensualesController@index',
]);

Route::get('almacenes/{id_almacen}/horas-mensuales/nuevo', [
    'as' => 'horas-mensuales.create',
    'uses' => 'Almacenes\HorasMensualesController@create',
]);

Route::post('almacenes/{id_almacen}/horas-mensuales', [
    'as' => 'horas-mensuales.store',
    'uses' => 'Almacenes\HorasMensualesController@store',
]);

Route::get('almacenes/{id_almacen}/horas-mensuales/{id}/modificar', [
    'as' => 'horas-mensuales.edit',
    'uses' => 'Almacenes\HorasMensualesController@edit',
]);

Route::patch('almacenes/{id_almacen}/horas-mensuales/{id}', [
    'as' => 'horas-mensuales.update',
    'uses' => 'Almacenes\HorasMensualesController@update',
]);

Route::delete('almacenes/{id_almacen}/horas-mensuales/{id}', [
    'as' => 'horas-mensuales.delete',
    'uses' => 'Almacenes\HorasMensualesController@destroy',
]);


/**
 * Reportes de Operacion
 */
Route::group(['prefix' => 'almacenes/{id}/'], function()
{
    Route::get('reporte-actividades', [
        'as' => 'reportes.index',
        'uses' => 'ReportesActividad\ReportesActividadController@index'
    ]);

    Route::get('reporte-actividades/iniciar', [
        'as' => 'reportes.create',
        'uses' => 'ReportesActividad\ReportesActividadController@create'
    ]);

    Route::post('reporte-actividades', [
        'as' => 'reportes.store',
        'uses' => 'ReportesActividad\ReportesActividadController@store'
    ]);

    Route::get('reporte-actividades/{idReporte}', [
        'as' => 'reportes.show',
        'uses' => 'ReportesActividad\ReportesActividadController@show'
    ]);

    Route::get('reporte-actividades/{idReporte}/modificar', [
        'as' => 'reportes.edit',
        'uses' => 'ReportesActividad\ReportesActividadController@edit'
    ]);

    Route::patch('reporte-actividades/{idReporte}', [
        'as' => 'reportes.update',
        'uses' => 'ReportesActividad\ReportesActividadController@update'
    ]);

    Route::get('reporte-actividades/{idReporte}/aprobar', [
        'as' => 'reportes.aprobar',
        'uses' => 'ReportesActividad\ReportesActividadController@aprobar'
    ]);

    Route::patch('reporte-actividades/{idReporte}/cierre', [
        'as' => 'reportes.cierre',
        'uses' => 'ReportesActividad\ReportesActividadController@cerrarReporte'
    ]);

    Route::delete('reporte-actividades/{idReporte}', [
        'as' => 'reportes.destroy',
        'uses' => 'ReportesActividad\ReportesActividadController@destroy'
    ]);

    /**
     * Actividades del Reporte
     */
    Route::get('reporte-actividades/{idReporte}/actividades/reportar', [
        'as' => 'actividades.create',
        'uses' => 'ReportesActividad\ActividadesController@create'
    ]);

    Route::post('reporte-actividades/{idReporte}/actividades', [
        'as' => 'actividades.store',
        'uses' => 'ReportesActividad\ActividadesController@store'
    ]);

    Route::delete('reporte-actividades/{idReporte}/actividades/{idActividad}', [
        'as' => 'actividades.delete',
        'uses' => 'ReportesActividad\ActividadesController@destroy'
    ]);
});


/**
 * Conciliacion de Operacion
 */
Route::get('conciliacion/', [
    'as' => 'conciliacion.proveedores',
    'uses' => 'Conciliacion\ConciliacionesController@showProveedores'
]);

Route::get('conciliacion/{idEmpresa}/almacenes', [
    'as' => 'conciliacion.almacenes',
    'uses' => 'Conciliacion\ConciliacionesController@showAlmacenes'
]);

Route::get('conciliacion/{idEmpresa}/almacenes/{idAlmacen}/conciliaciones', [
    'as' => 'conciliacion.index',
    'uses' => 'Conciliacion\ConciliacionesController@index'
]);

Route::get('conciliacion/{idEmpresa}/almacenes/{idAlmacen}/conciliaciones/conciliar', [
    'as' => 'conciliacion.conciliar',
    'uses' => 'Conciliacion\ConciliacionesController@create'
]);

Route::post('conciliacion/{idEmpresa}/almacenes/{idAlmacen}/conciliaciones', [
    'as' => 'conciliacion.store',
    'uses' => 'Conciliacion\ConciliacionesController@store'
]);

Route::get('conciliacion/{idEmpresa}/almacenes/{idAlmacen}/conciliaciones/{id}', [
    'as' => 'conciliacion.edit',
    'uses' => 'Conciliacion\ConciliacionesController@edit'
]);

Route::patch('conciliacion/{idEmpresa}/almacenes/{idAlmacen}/conciliaciones/{id}', [
    'as' => 'conciliacion.update',
    'uses' => 'Conciliacion\ConciliacionesController@update'
]);

delete('conciliacion/{idEmpresa}/almacenes/{idAlmacen}/conciliaciones/{id}', [
    'as' => 'conciliacion.delete',
    'uses' => 'Conciliacion\ConciliacionesController@destroy'
]);

//\Event::listen('Ghi.*', 'Ghi\Conciliacion\Domain\GeneradorPartesUso');