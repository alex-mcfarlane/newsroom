<?php

/*
 * API routes
 */
Route::group(['prefix' => 'api'], function(){
    Route::get('categories/newestArticles', 'CategoriesNewestArticlesController@index');

    Route::post('articles/{id}/images', 'ArticlesController@addImage');
    Route::put('articles/{id}/feature', 'FeaturedArticlesController@feature');
    Route::delete('articles/{id}/feature', 'FeaturedArticlesController@unfeature');
    
    Route::resource('users', 'API\UsersAPIController', ['only' =>
        'store'
    ]);

    Route::resource('articles', 'ArticlesController', ['only' =>
        ['store', 'index', 'show']
    ]);

    Route::resource('categories', 'CategoriesController', ['only' => [
        'store', 'show'
    ]]);
});

/*
 * Web Routes
 */
Route::group(['middleware' => ['web']], function() {
    Route::get('/', 'HomeController@index');

    Route::get('/register', 'UsersController@create');
    Route::post('/users', 'UsersController@store');
    
    Route::auth();
});