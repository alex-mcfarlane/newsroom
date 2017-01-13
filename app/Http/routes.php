<?php

/*
 * API routes
 */
Route::group(['prefix' => 'api'], function(){
    Route::get('categories/newestArticles', 'CategoriesNewestArticlesController@index');

    Route::resource('categories', 'CategoriesController', ['only' => [
        'store', 'show'
    ]]);
    
    Route::PUT('articles/{id}/feature', 'FeaturedArticlesController@feature');
    Route::DELETE('articles/{id}/feature', 'FeaturedArticlesController@unfeature');
});

Route::post('/api/articles', 'ArticlesController@store');

/*
 * Web Routes
 */
Route::get('/', 'HomeController@index');


//Route::auth();
