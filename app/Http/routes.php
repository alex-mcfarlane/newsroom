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
    Route::resource('articles', 'ArticlesController', ['only' => [
        'store', 'show'
    ]]);

    Route::resource('categories', 'CategoriesController', ['only' => [
        'store', 'show'
    ]]);
});

Route::post('/api/articles', 'ArticlesController@store');
