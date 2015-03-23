<?php

/**
 * Paginas
 */

get('/', [
    'as' => 'pages.home',
    'uses' => 'PagesController@home',
]);

get('/obras', [
    'as' => 'pages.obras',
    'uses' => 'PagesController@obras',
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


//    /**
//     * Conceptos
//     */
//
//    get('conceptos/{idConcepto?}', [
//        'as' => 'conceptos.index',
//        'uses' => 'Conceptos\ConceptosController@index'
//    ]);
//
//});
//
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
    get('conceptos/{id}', 'Api\ConceptosController@show');

    get('conceptos', 'Api\ConceptosController@lists');
});