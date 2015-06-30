<?php

/**
 * Paginas
 */
get('/', [
    'as' => 'pages.home',
    'uses' => 'PagesController@home'
]);

get('/obras', [
    'before' => 'auth',
    'as' => 'pages.obras',
    'uses' => 'PagesController@obras'
]);

get('/context/{databaseName}/{idObra}', [
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
get('auth/login', [
    'as' => 'auth.login',
    'uses' => 'Auth\AuthController@getLogin'
]);

post('auth/login', [
    'as' => 'auth.login',
    'uses' => 'Auth\AuthController@postLogin'
]);

get('auth/logout', [
    'as' => 'auth.logout',
    'uses' => 'Auth\AuthController@getLogout'
]);

Route::group(['prefix' => 'api'], function()
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
    get('conceptos', 'Api\ConceptosController@lists');
});


/**
 * Almacenes
 */
get('almacenes', [
    'as' => 'almacenes.index',
    'uses' => 'Almacenes\AlmacenesController@index',
]);

get('almacenes/nuevo', [
    'as' => 'almacenes.create',
    'uses' => 'Almacenes\AlmacenesController@create',
]);

get('almacenes/{id}', [
    'as' => 'almacenes.show',
    'uses' => 'Almacenes\AlmacenesController@show',
])->where('id', '[0-9]+');

post('almacenes', [
    'as' => 'almacenes.store',
    'uses' => 'Almacenes\AlmacenesController@store',
]);

get('almacenes/{id}/modificar', [
    'as' => 'almacenes.edit',
    'uses' => 'Almacenes\AlmacenesController@edit',
]);

patch('almacenes/{id}', [
    'as' => 'almacenes.update',
    'uses' => 'Almacenes\AlmacenesController@update',
]);

/**
 * Horas Mensuales
 */

get('almacenes/{id}/horas-mensuales', [
    'as' => 'horas-mensuales.index',
    'uses' => 'Almacenes\HorasMensualesController@index',
]);

get('almacenes/{id}/horas-mensuales/nuevo', [
    'as' => 'horas-mensuales.create',
    'uses' => 'Almacenes\HorasMensualesController@create',
]);

post('almacenes/{id}/horas-mensuales', [
    'as' => 'horas-mensuales.store',
    'uses' => 'Almacenes\HorasMensualesController@store',
]);

patch('almacenes/{idAlmacen}/horas-mensuales/{id}', [
    'as' => 'horas-mensuales.index',
    'uses' => 'Almacenes\HorasMensualesController@update',
]);


/**
 * Reportes de Operacion
 */
Route::group(['prefix' => 'almacenes/{id}/'], function()
{
    get('reporte-actividades', [
        'as' => 'reportes.index',
        'uses' => 'ReportesActividad\ReportesActividadController@index'
    ]);

    get('reporte-actividades/iniciar', [
        'as' => 'reportes.create',
        'uses' => 'ReportesActividad\ReportesActividadController@create'
    ]);

    post('reporte-actividades', [
        'as' => 'reportes.store',
        'uses' => 'ReportesActividad\ReportesActividadController@store'
    ]);

    get('reporte-actividades/{idReporte}', [
        'as' => 'reportes.show',
        'uses' => 'ReportesActividad\ReportesActividadController@show'
    ]);

    get('reporte-actividades/{idReporte}/modificar', [
        'as' => 'reportes.edit',
        'uses' => 'ReportesActividad\ReportesActividadController@edit'
    ]);

    patch('reporte-actividades/{idReporte}', [
        'as' => 'reportes.update',
        'uses' => 'ReportesActividad\ReportesActividadController@update'
    ]);

    get('reporte-actividades/{idReporte}/cierre', [
        'as' => 'reportes.cierre',
        'uses' => 'ReportesActividad\ReportesActividadController@cierre'
    ]);

    put('reporte-actividades/{idReporte}/cierre', [
        'as' => 'reportes.cierre',
        'uses' => 'ReportesActividad\ReportesActividadController@cerrarReporte'
    ]);

    delete('reporte-actividades/{idReporte}', [
        'as' => 'reportes.destroy',
        'uses' => 'ReportesActividad\ReportesActividadController@destroy'
    ]);


    /**
     * Actividades del Reporte
     */

    get('reporte-actividades/{idReporte}/actividades/reportar', [
        'as' => 'actividades.create',
        'uses' => 'ReportesActividad\ActividadesController@create'
    ]);

    post('reporte-actividades/{idReporte}/actividades', [
        'as' => 'actividades.store',
        'uses' => 'ReportesActividad\ActividadesController@store'
    ]);

    delete('reporte-actividades/{idReporte}/actividades/{idActividad}', [
        'as' => 'actividades.delete',
        'uses' => 'ReportesActividad\ActividadesController@destroy'
    ]);
});


/**
 * Conciliacion de Operacion
 */
get('conciliacion/', [
    'as' => 'conciliacion.proveedores',
    'uses' => 'Conciliacion\ConciliacionesController@proveedores'
]);

get('conciliacion/{idEmpresa}/almacenes', [
    'as' => 'conciliacion.almacenes',
    'uses' => 'Conciliacion\ConciliacionesController@almacenes'
]);

get('conciliacion/{idEmpresa}/almacenes/{idAlmacen}/conciliaciones', [
    'as' => 'conciliacion.index',
    'uses' => 'Conciliacion\ConciliacionesController@index'
]);

get('conciliacion/{idEmpresa}/almacenes/{idAlmacen}/conciliar', [
    'as' => 'conciliacion.conciliar',
    'uses' => 'Conciliacion\ConciliacionesController@create'
]);

post('conciliacion/{idEmpresa}/almacenes/{idAlmacen}/conciliar', [
    'as' => 'conciliacion.store',
    'uses' => 'Conciliacion\ConciliacionesController@store'
]);

get('conciliacion/{idEmpresa}/almacenes/{idAlmacen}/conciliaciones/{id}', [
    'as' => 'conciliacion.edit',
    'uses' => 'Conciliacion\ConciliacionesController@edit'
]);

put('conciliacion/{idEmpresa}/almacenes/{idAlmacen}/conciliaciones/{id}', [
    'as' => 'conciliacion.update',
    'uses' => 'Conciliacion\ConciliacionesController@update'
]);

\Event::listen('Ghi.*', 'Ghi\Conciliacion\Domain\GeneradorPartesUso');