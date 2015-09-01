<?php

// Rutas de paginas...
Route::get('/', 'PagesController@home');

// Rutas de autenticacion...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Rutas de tipos de area...
Route::get('tipos', ['as' => 'tipos.index', 'uses' => 'TiposController@index']);
Route::get('tipos/nuevo', ['as' => 'tipos.create', 'uses' => 'TiposController@create']);
Route::post('tipos/nuevo', ['as' => 'tipos.store', 'uses' => 'TiposController@store']);
Route::get('tipos/{id}/modificar', ['as' => 'tipos.edit', 'uses' => 'TiposController@edit']);
Route::patch('tipos/{id}', ['as' => 'tipos.update', 'uses' => 'TiposController@update']);
Route::delete('tipos/{id}', ['as' => 'tipos.delete', 'uses' => 'TiposController@destroy']);

// Rutas de subtipos de area...
Route::get('tipos/{tipo_id}/subtipos/nuevo', ['as' => 'subtipos.create', 'uses' => 'SubTiposController@create']);
Route::post('tipos/{tipo_id}/subtipos', ['as' => 'subtipos.store', 'uses' => 'SubTiposController@store']);
Route::get('tipos/{tipo_id}/subtipos/{subtipo_id}/modificar', ['as' => 'subtipos.edit', 'uses' => 'SubTiposController@edit']);
Route::patch('tipos/{tipo_id}/subtipos/{subtipo_id}', ['as' => 'subtipos.update', 'uses' => 'SubTiposController@update']);
Route::delete('tipos/{tipo_id}/subtipos/{subtipo_id}', ['as' => 'subtipos.delete', 'uses' => 'SubTiposController@destroy']);

// Rutas de areas...
Route::get('areas', ['as' => 'areas.index', 'uses' => 'AreasController@index']);
Route::get('areas/nueva', ['as' => 'areas.create', 'uses' => 'AreasController@create']);
Route::post('areas', ['as' => 'areas.store', 'uses' => 'AreasController@store']);
Route::get('areas/{id}/modificar', ['as' => 'areas.edit', 'uses' => 'AreasController@edit']);
Route::patch('areas/{id}', ['as' => 'areas.update', 'uses' => 'AreasController@update']);
Route::delete('areas/{id}', ['as' => 'areas.delete', 'uses' => 'AreasController@destroy']);
