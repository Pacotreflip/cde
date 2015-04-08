<?php

/**
 * Conciliacion de Operacion
 */

get('conciliacion/', [
    'as' => 'conciliacion.proveedores',
    'uses' => 'ConciliacionController@proveedores'
]);

get('conciliacion/{idEmpresa}/almacenes', [
    'as' => 'conciliacion.almacenes',
    'uses' => 'ConciliacionController@almacenes'
]);

get('conciliacion/{idEmpresa}/almacenes/{idAlmacen}/conciliaciones', [
    'as' => 'conciliacion.index',
    'uses' => 'ConciliacionController@index'
]);

get('conciliacion/{idEmpresa}/almacenes/{idAlmacen}/conciliar', [
    'as' => 'conciliacion.conciliar',
    'uses' => 'ConciliacionController@create'
]);

post('conciliacion/{idEmpresa}/almacenes/{idAlmacen}/conciliar', [
    'as' => 'conciliacion.store',
    'uses' => 'ConciliacionController@store'
]);

get('conciliacion/{idEmpresa}/almacenes/{idAlmacen}/conciliaciones/{id}', [
    'as' => 'conciliacion.edit',
    'uses' => 'ConciliacionController@edit'
]);

put('conciliacion/{idEmpresa}/almacenes/{idAlmacen}/conciliaciones/{id}', [
    'as' => 'conciliacion.update',
    'uses' => 'ConciliacionController@update'
]);

\Event::listen('Ghi.*', 'Ghi\Conciliacion\Domain\GeneradorPartesUso');
