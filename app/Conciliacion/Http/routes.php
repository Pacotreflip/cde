<?php

/**
 * Conciliacion de Operacion
 */

get('conciliacion/', [
    'as' => 'conciliacion.proveedores',
    'uses' => 'ConciliacionController@proveedores'
]);

get('conciliacion/{idProveedor}/equipos', [
    'as' => 'conciliacion.equipos',
    'uses' => 'ConciliacionController@equipos'
]);

get('conciliacion/{idProveedor}/equipos/{idEquipo}/periodos', [
    'as' => 'conciliacion.index',
    'uses' => 'ConciliacionController@index'
]);

get('conciliacion/{idProveedor}/equipos/{idEquipo}/conciliar', [
    'as' => 'conciliacion.conciliar',
    'uses' => 'ConciliacionController@create'
]);

post('conciliacion/{idProveedor}/equipos/{idEquipo}/conciliar', [
    'as' => 'conciliacion.store',
    'uses' => 'ConciliacionController@store'
]);

get('conciliacion/{idProveedor}/equipos/{idEquipo}/periodos/{idConciliacion}', [
    'as' => 'conciliacion.edit',
    'uses' => 'ConciliacionController@edit'
]);

put('conciliacion/{idProveedor}/equipos/{idEquipo}/periodos/{idConciliacion}', [
    'as' => 'conciliacion.update',
    'uses' => 'ConciliacionController@update'
]);