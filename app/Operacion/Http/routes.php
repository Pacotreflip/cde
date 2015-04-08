<?php

/**
 * Reportes de Operacion
 */

Route::group(['prefix' => 'almacenes/{id}/'], function()
{
    get('reporte-actividades', [
        'as' => 'reportes.index',
        'uses' => 'ReportesActividadController@index'
    ]);

    get('reporte-actividades/iniciar', [
        'as' => 'reportes.create',
        'uses' => 'ReportesActividadController@create'
    ]);

    post('reporte-actividades', [
        'as' => 'reportes.store',
        'uses' => 'ReportesActividadController@store'
    ]);

    get('reporte-actividades/{idReporte}', [
        'as' => 'reportes.show',
        'uses' => 'ReportesActividadController@show'
    ]);

    get('reporte-actividades/{idReporte}/modificar', [
        'as' => 'reportes.edit',
        'uses' => 'ReportesActividadController@edit'
    ]);

    patch('reporte-actividades/{idReporte}', [
        'as' => 'reportes.update',
        'uses' => 'ReportesActividadController@update'
    ]);

    get('reporte-actividades/{idReporte}/cierre', [
        'as' => 'reportes.cierre',
        'uses' => 'ReportesActividadController@cierre'
    ]);

    put('reporte-actividades/{idReporte}/cierre', [
        'as' => 'reportes.cierre',
        'uses' => 'ReportesActividadController@cerrarReporte'
    ]);

    delete('reporte-actividades/{idReporte}', [
        'as' => 'reportes.destroy',
        'uses' => 'ReportesActividadController@destroy'
    ]);


    /**
     * Actividades del Reporte
     */

    get('reporte-actividades/{idReporte}/actividades/reportar', [
        'as' => 'actividades.create',
        'uses' => 'ActividadesController@create'
    ]);

    post('reporte-actividades/{idReporte}/actividades', [
        'as' => 'actividades.store',
        'uses' => 'ActividadesController@store'
    ]);

    delete('reporte-actividades/{idReporte}/actividades/{idActividad}', [
        'as' => 'actividades.delete',
        'uses' => 'ActividadesController@destroy'
    ]);
});
