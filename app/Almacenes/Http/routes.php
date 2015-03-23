<?php

/**
 * Almacenes
 */

get('almacenes', [
   'as' => 'almacenes.index',
   'uses' => 'AlmacenesController@index',
]);

get('almacenes/nuevo', [
    'as' => 'almacenes.create',
    'uses' => 'AlmacenesController@create',
]);

get('almacenes/{id}', [
    'as' => 'almacenes.show',
    'uses' => 'AlmacenesController@show',
])->where('id', '[0-9]+');

post('almacenes', [
    'as' => 'almacenes.store',
    'uses' => 'AlmacenesController@store',
]);

get('almacenes/{id}/modificar', [
    'as' => 'almacenes.edit',
    'uses' => 'AlmacenesController@edit',
]);

patch('almacenes/{id}', [
    'as' => 'almacenes.update',
    'uses' => 'AlmacenesController@update',
]);

/**
 * Horas Mensuales
 */

get('almacenes/{id}/horas-mensuales', [
    'as' => 'horas-mensuales.index',
    'uses' => 'HorasMensualesController@index',
]);

get('almacenes/{id}/horas-mensuales/nuevo', [
    'as' => 'horas-mensuales.create',
    'uses' => 'HorasMensualesController@create',
]);

post('almacenes/{id}/horas-mensuales', [
    'as' => 'horas-mensuales.store',
    'uses' => 'HorasMensualesController@store',
]);

patch('almacenes/{idAlmacen}/horas-mensuales/{id}', [
    'as' => 'horas-mensuales.index',
    'uses' => 'HorasMensualesController@update',
]);