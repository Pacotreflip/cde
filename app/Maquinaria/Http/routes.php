<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(['before' => 'auth|tenant'], function()
{
    /**
     * Reporte de Operacion
     */
    get('operacion/', [
        'as' => 'operacion.equipos',
        'uses' => 'Maquinaria\Http\Controllers\Operacion\OperacionController@equipos'
    ]);

    get('operacion/{idEquipo}', [
        'as' => 'operacion.index',
        'uses' => 'Maquinaria\Http\Controllers\Operacion\OperacionController@index'
    ]);

    get('operacion/{idEquipo}/iniciar', [
        'as' => 'operacion.create',
        'uses' => 'Maquinaria\Http\Controllers\Operacion\OperacionController@create'
    ]);

    post('operacion/{idEquipo}', [
        'as' => 'operacion.store',
        'uses' => 'Maquinaria\Http\Controllers\Operacion\OperacionController@store'
    ]);

    get('operacion/{idEquipo}/{fecha}', [
        'as' => 'operacion.show',
        'uses' => 'Maquinaria\Http\Controllers\Operacion\OperacionController@show'
    ]);

    get('operacion/{idEquipo}/{fecha}/modificar', [
        'as' => 'operacion.edit',
        'uses' => 'Maquinaria\Http\Controllers\Operacion\OperacionController@edit'
    ]);

    put('operacion/{idEquipo}/{fecha}', [
        'as' => 'operacion.update',
        'uses' => 'Maquinaria\Http\Controllers\Operacion\OperacionController@update'
    ]);

    get('operacion/{idEquipo}/{fecha}/cierre', [
        'as' => 'operacion.cierre',
        'uses' => 'Maquinaria\Http\Controllers\Operacion\OperacionController@cierre'
    ]);

    put('operacion/{idEquipo}/{fecha}/cierre', [
        'as' => 'operacion.cierre',
        'uses' => 'Maquinaria\Http\Controllers\Operacion\OperacionController@cerrarReporte'
    ]);

    delete('operacion/{idEquipo}/{fecha}', [
        'as' => 'operacion.destroy',
        'uses' => 'Maquinaria\Http\Controllers\Operacion\OperacionController@destroy'
    ]);

    /**
     * Horas reporte
     */
    get('operacion/{idEquipo}/{fecha}/horas/reportar', [
        'as' => 'horas.create',
        'uses' => 'Maquinaria\Http\Controllers\Operacion\HorasController@create'
    ]);

    post('operacion/{idEquipo}/{fecha}/horas/reportar', [
        'as' => 'horas.store',
        'uses' => 'Maquinaria\Http\Controllers\Operacion\HorasController@store'
    ]);

    delete('operacion/{idEquipo}/{fecha}/horas/{idHora}', [
        'as' => 'horas.delete',
        'uses' => 'Maquinaria\Http\Controllers\Operacion\HorasController@destroy'
    ]);


    /**
     * Conciliacion de Operacion
     */

    get('conciliacion/', [
        'as' => 'conciliacion.proveedores',
        'uses' => 'Maquinaria\Http\Controllers\Conciliacion\ConciliacionController@proveedores'
    ]);

    get('conciliacion/{idProveedor}/equipos', [
        'as' => 'conciliacion.equipos',
        'uses' => 'Maquinaria\Http\Controllers\Conciliacion\ConciliacionController@equipos'
    ]);

    get('conciliacion/{idProveedor}/equipos/{idEquipo}/periodos', [
        'as' => 'conciliacion.index',
        'uses' => 'Maquinaria\Http\Controllers\Conciliacion\ConciliacionController@index'
    ]);

    get('conciliacion/{idProveedor}/equipos/{idEquipo}/conciliar', [
        'as' => 'conciliacion.conciliar',
        'uses' => 'Maquinaria\Http\Controllers\Conciliacion\ConciliacionController@create'
    ]);

    post('conciliacion/{idProveedor}/equipos/{idEquipo}/conciliar', [
        'as' => 'conciliacion.store',
        'uses' => 'Maquinaria\Http\Controllers\Conciliacion\ConciliacionController@store'
    ]);

    get('conciliacion/{idProveedor}/equipos/{idEquipo}/periodos/{idConciliacion}', [
        'as' => 'conciliacion.edit',
        'uses' => 'Maquinaria\Http\Controllers\Conciliacion\ConciliacionController@edit'
    ]);

    put('conciliacion/{idProveedor}/equipos/{idEquipo}/periodos/{idConciliacion}', [
        'as' => 'conciliacion.update',
        'uses' => 'Maquinaria\Http\Controllers\Conciliacion\ConciliacionController@update'
    ]);

});

Route::group(['prefix' => 'api'], function()
{
//    if ( ! Auth::check())
//    {
//        \Auth::loginUsingId(1);
//    }

    get('equipos/{id}', 'Maquinaria\Http\Controllers\Api\EquiposController@show');
    get('equipos', 'Maquinaria\Http\Controllers\Api\EquiposController@lists');
});

\Event::listen('Ghi.*', 'Ghi\Maquinaria\Domain\Conciliacion\GeneradorPartesUso');