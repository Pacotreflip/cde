<?php

/**
 * Reportes de Operacion
 */

get('reporte-actividades', [
    'as' => 'reportes.almacenes',
    'uses' => 'ReportesActividadController@almacenes'
]);

get('reporte-actividades/{idAlmacen}', [
    'as' => 'reportes.index',
    'uses' => 'ReportesActividadController@index'
]);

get('reporte-actividades/{idAlmacen}/iniciar', [
    'as' => 'reportes.create',
    'uses' => 'ReportesActividadController@create'
]);

post('reporte-actividades/{idAlmacen}', [
    'as' => 'reportes.store',
    'uses' => 'ReportesActividadController@store'
]);

get('reporte-actividades/{idAlmacen}/{idReporte}', [
    'as' => 'reportes.show',
    'uses' => 'ReportesActividadController@show'
]);

get('reporte-actividades/{idAlmacen}/{idReporte}/modificar', [
    'as' => 'reportes.edit',
    'uses' => 'ReportesActividadController@edit'
]);

patch('reporte-actividades/{idAlmacen}/{idReporte}', [
    'as' => 'reportes.update',
    'uses' => 'ReportesActividadController@update'
]);

get('reporte-actividades/{idAlmacen}/{idReporte}/cierre', [
    'as' => 'reportes.cierre',
    'uses' => 'ReportesActividadController@cierre'
]);

put('reporte-actividades/{idAlmacen}/{idReporte}/cierre', [
    'as' => 'reportes.cierre',
    'uses' => 'ReportesActividadController@cerrarReporte'
]);

delete('reporte-actividades/{idAlmacen}/{idReporte}', [
    'as' => 'reportes.destroy',
    'uses' => 'ReportesActividadController@destroy'
]);


/**
 * Horas reporte
 */

get('reporte-actividades/{idAlmacen}/{idReporte}/actividades/reportar', [
    'as' => 'actividades.create',
    'uses' => 'ActividadesController@create'
]);

post('reporte-actividades/{idAlmacen}/{idReporte}/actividades/reportar', [
    'as' => 'actividades.store',
    'uses' => 'ActividadesController@store'
]);

delete('reporte-actividades/{idAlmacen}/{idReporte}/actividades/{idActividad}', [
    'as' => 'actividades.delete',
    'uses' => 'ActividadesController@destroy'
]);


\Event::listen('Ghi.*', 'Ghi\Conciliacion\Domain\GeneradorPartesUso');