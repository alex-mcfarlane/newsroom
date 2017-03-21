<?php

/*
 * API routes
 */
Route::group(['prefix' => 'api'], function(){
    Route::post('auth', "Auth\AuthController@authenticate");
    
    Route::get('categories/newestArticles', 'CategoriesNewestArticlesController@index');

    Route::post('articles/{id}/images', 'API\ArticlesAPIController@addImage');
    
    Route::post('articles/{id}/featured', 'FeaturedArticlesController@store');
    Route::delete('articles/{id}/featured', 'FeaturedArticlesController@destroy');
    Route::get('articles/featured', 'FeaturedArticlesController@index');
    
    Route::put('articles/{id}/headline', 'API\ArticlesAPIController@makeHeadliner');
    Route::delete('articles/{id}/feature', 'FeaturedArticlesController@removeHeadliner');
    
    Route::resource('users', 'API\UsersAPIController', ['only' =>
        'store'
    ]);

    Route::resource('articles', 'API\ArticlesAPIController', ['only' =>
        ['store', 'update', 'index', 'show']
    ]);

    Route::resource('categories', 'API\CategoriesAPIController', ['only' => [
        'index', 'store', 'show'
    ]]);
});

/*
 * Web Routes
 */
Route::group(['middleware' => ['web']], function() {
    Route::get('/register', 'UsersController@create');
    Route::post('/users', 'UsersController@store');
    
    Route::get('/login', function(){
       return view('auth.login'); 
    });
    
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
    
    Route::get('/articles/{id}', 'ArticlesController@show');
    Route::put('/articles/{id}', 'ArticlesController@update');
    Route::delete('/articles/{id}', 'ArticlesController@delete');
    
    Route::get('/categories/{id}', 'CategoriesController@show');
    
    //Route::auth();
});