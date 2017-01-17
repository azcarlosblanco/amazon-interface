<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

// Authentication Routes...
Route::get('iniciar-sesion', 'Auth\LoginController@showLoginForm');

Route::post('iniciar-sesion', 'Auth\LoginController@login');

Route::post('salir', 'Auth\LoginController@logout');

Route::get('localhost/renovar-token', [
    'uses' => 'HomeController@renovateMeliToken',
    'as' => 'renovate.meli',
]);

Route::group(['middleware' => 'credentials'], function()
{
    Route::post('/notification', [
        'uses' => 'ProductsController@meliNotification',
        'as'   => 'products.notification'
    ]);

    Route::get('check-publicaciones', [
        'uses' => 'ProductsController@checkMeliOrders',
        'as'   => 'products.checkMeliOrders'
    ]);

    Route::get('amazon/procesar/masivo', [
        'uses' => 'ProductsController@searchesHandler',
        'as' => 'products.handle.searches',
    ]);


});


Route::group(['middleware' => 'auth', ], function()
{
    Route::get('/', [
        'uses' => 'HomeController@index',
        'as' => 'home',
    ]);

    Route::group(['middleware' => 'credentials'], function()
    {
        Route::get('amazon', [
            'uses' => 'AmazonSearchController@index',
            'as' => 'amazon.index',
        ]);

        Route::get('amazon/buscar', [
            'uses' => 'AmazonSearchController@itemSearch',
            'as' => 'amazon.search',
        ]);

        Route::get('amazon/categorias', [
            'uses' => 'AmazonSearchController@browseNodeLookup',
            'as' => 'amazon.browse-node-lookup',
        ]);

        // Products CRUD
        Route::resource('/publicaciones', 'ProductsController');
        Route::get('publicaciones/{id}/destroy', [
            'uses' => 'ProductsController@destroy',
            'as'   => 'products.destroy'
        ]);
        Route::get('publicaciones/{id}/resend-mercadolibre', [
            'uses' => 'ProductsController@reSendMeli',
            'as'   => 'products.reSendMeli'
        ]);
        Route::post('/publicaciones', [
            'uses' => 'ProductsController@sendMeli',
            'as'   => 'products.sendMeli'
        ]);
        Route::post('/publicaciones-linio', [
            'uses' => 'ProductsController@sendLinio',
            'as'   => 'products.sendLinio'
        ]);
        Route::post('/publicaciones-sendmelimasive', [
            'uses' => 'ProductsController@sendMeliMasive',
            'as'   => 'products.sendMeliMasive'
        ]);

        Route::get('return-back-mercadolibre', [
            'uses' => 'ProductsController@returnMeliOrders',
            'as'   => 'products.returnMeliOrders'
        ]);
        Route::get('/actualizar-orden/{id}', [
            'uses' => 'ProductsController@updateOrder',
            'as'   => 'products.updateOrder'
        ]);

        Route::get('amazon/masivo', [
            'uses' => 'ProductsController@findAsinByCategory',
            'as' => 'amazon.masive',
        ]);

        Route::get('amazon/masivo/categorias', [
            'uses' => 'ProductsController@findAsinByCategory',
            'as' => 'amazon.masive.categories',
        ]);

        // Users CRUD
        Route::group(['middleware' => 'role:superadmin'], function()
        {
            Route::resource('/usuarios','UsersController');
            Route::get('usuarios/{id}/destroy', [
            'uses' => 'UsersController@destroy',
            'as'   => 'usuarios.destroy'
            ]);

            // Parameters CRUD
            Route::resource('/parametros','ParametersController');

        });

        // Alerts CRUD
        Route::resource('/alertas','AlertsController');
        Route::get('alertas/{id}/destroy', [
        'uses' => 'AlertsController@destroy',
        'as'   => 'alertas.destroy'
        ]);

        // Alerts CRUD
        Route::resource('/productos','ProductsController');
        Route::get('productos/{id}/destroy', [
        'uses' => 'ProductsController@destroy',
        'as'   => 'productos.destroy'
        ]);

        // Carts CRUD
        Route::resource('/carritos','CartsController');
        Route::get('carritos/{id}/destroy', [
        'uses' => 'CartsController@destroy',
        'as'   => 'carritos.destroy'
        ]);
        Route::get('carritos/{id}/processed', [
        'uses' => 'CartsController@processed',
        'as'   => 'carritos.processed'
        ]);

    });

});
