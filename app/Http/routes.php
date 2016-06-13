<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

# Index and video ID fetching routes
Route::get('/', 'IndexController@index');
Route::get('/videoid', 'IndexController@videoid');
Route::get('/save/{id}', 'IndexController@save_song');
Route::get('/saved', 'IndexController@saved');

# Facebook Login
Route::get('/redirect', 'SocialAuthController@redirect');
Route::get('/callback', 'SocialAuthController@callback');
Route::get('/logout', 'SocialAuthController@logout');