<?php

// Rutas de paginas...
Route::get('/', 'PagesController@home');

// Rutas de autenticacion...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Rutas de tipos y subtipos de area...
Route::get('tipos-area', 'TiposAreaController@index');
Route::get('tipos-area/nuevo', 'TiposAreaController@create');
Route::post('tipos-area/nuevo', 'TiposAreaController@store');
Route::get('tipos-area/{id_tipo}', 'TiposAreaController@edit');
Route::patch('tipos-area/{id_tipo}', 'TiposAreaController@update');
Route::get('tipos-area/{id_tipo}/subtipos/nuevo', 'SubTiposAreaController@create');
Route::post('tipos-area/{id_tipo}/subtipos', 'SubTiposAreaController@store');
Route::get('tipos-area/{id_tipo}/subtipos/{id_subtipo}', 'SubTiposAreaController@edit');
Route::patch('tipos-area/{id_tipo}/subtipos/{id_subtipo}', 'SubTiposController@update');