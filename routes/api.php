<?php


Route::group(['middleware' => 'cors'], function () {

    /* Login & Register */
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');

    Route::group(['middleware' => 'jwt.auth'], function () {

        /* List */
        Route::get('list', 'ListController@index');
        Route::get('list/{id}', 'ListController@getListItems');
        Route::post('list/create', 'ListController@store');
        Route::put('list/{id}/edit', 'ListController@update');
        Route::delete('list/{id}/delete', 'ListController@destroy');

        /* Item */
        Route::post('list/{id}/item', 'ItemController@store');
        Route::put('list/{list_id}/item/{id}/edit', 'ItemController@update');
        Route::delete('list/{list_id}/item/{id}/delete', 'ItemController@destroy');

    });

});