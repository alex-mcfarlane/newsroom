<?php

/*
 * API routes
 */
Route::group(['prefix' => 'api'], function(){
    Route::get('categories/newestArticles', 'CategoriesNewestArticlesController@index');

    Route::resource('categories', 'CategoriesController', ['only' => [
        'store', 'show'
    ]]);
    
    Route::put('articles/{id}/feature', 'FeaturedArticlesController@feature');
    Route::delete('articles/{id}/feature', 'FeaturedArticlesController@unfeature');
    
    Route::resource('users', 'API\UsersAPIController', ['only' =>
        'store'
    ]);
});

Route::post('/api/articles', 'ArticlesController@store');

/*
 * Web Routes
 */
Route::group(['middleware' => ['web']], function() {
    Route::get('/', 'HomeController@index');

    Route::get('/register', 'UsersController@create');

    Route::post('/users', 'UsersController@store');
});