<?php

/*
 * API routes
 */
Route::group(['prefix' => 'api'], function(){
    Route::get('categories/newestArticles', 'CategoriesNewestArticlesController@index');

    Route::post('articles/{id}/images', 'ArticlesAPIController@addImage');
    
    Route::post('articles/{id}/featured', 'FeaturedArticlesController@store');
    Route::get('articles/featured', 'FeaturedArticlesController@index');
    
    Route::put('articles/{id}/headline', 'ArticlesAPIController@makeHeadliner');
    Route::delete('articles/{id}/feature', 'FeaturedArticlesController@removeHeadliner');
    
    Route::resource('users', 'API\UsersAPIController', ['only' =>
        'store'
    ]);

    Route::resource('articles', 'ArticlesAPIController', ['only' =>
        ['store', 'update', 'index', 'show']
    ]);

    Route::resource('categories', 'CategoriesAPIController', ['only' => [
        'index', 'store', 'show'
    ]]);
});

/*
 * Web Routes
 */
Route::group(['middleware' => ['web']], function() {
    Route::get('/register', 'UsersController@create');
    Route::post('/users', 'UsersController@store');
    
    Route::get('/', 'HomeController@index');
    
    Route::get('/articles/{id}', 'ArticlesController@show');

    Route::put('/articles/{id}', 'ArticlesController@update');
    
    Route::get('/categories/{id}', 'CategoriesController@show');
    
    Route::auth();
});