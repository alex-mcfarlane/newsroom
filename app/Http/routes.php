<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/home', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});

Route::group(['prefix' => 'api'], function(){
    Route::get('categories/newestArticles', 'CategoriesNewestArticlesController@index');

    Route::resource('categories', 'CategoriesController', ['only' => [
        'store', 'show'
    ]]);
    
    Route::PUT('articles/{id}/feature', 'FeaturedArticlesController@feature');
    Route::DELETE('articles/{id}/feature', 'FeaturedArticlesController@unfeature');
});

Route::post('/api/articles', 'ArticlesController@store');
