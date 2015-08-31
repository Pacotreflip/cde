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
Route::get('tipos/{id}', ['as' => 'tipos.edit', 'uses' => 'TiposController@edit']);
Route::patch('tipos/{id}', ['as' => 'tipos.update', 'uses' => 'TiposController@update']);
Route::delete('tipos/{id}', ['as' => 'tipos.delete', 'uses' => 'TiposController@destroy']);

// Rutas de subtipos de area...
Route::get('tipos/{tipo_id}/subtipos/nuevo', ['as' => 'subtipos.create', 'uses' => 'SubTiposController@create']);
Route::post('tipos/{tipo_id}/subtipos', ['as' => 'subtipos.store', 'uses' => 'SubTiposController@store']);
Route::get('tipos/{tipo_id}/subtipos/{subtipo_id}', ['as' => 'subtipos.edit', 'uses' => 'SubTiposController@edit']);
Route::patch('tipos/{tipo_id}/subtipos/{subtipo_id}', ['as' => 'subtipos.update', 'uses' => 'SubTiposController@update']);
Route::delete('tipos/{tipo_id}/subtipos/{subtipo_id}', ['as' => 'subtipos.delete', 'uses' => 'SubTiposController@destroy']);

