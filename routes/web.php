<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login','AuthController@login')->name('login');
Route::get('/sync','PublicController@index')->name('dashboard');
Route::post('/createYoutubePlaylist','PublicController@createYoutubePlaylist')->name('createYoutubePlaylist');
Route::post('/createSpotifyPlaylist','PublicController@createSpotifyPlaylist')->name('createSpotifyPlaylist');
Route::post('/similarSongs','PublicController@searchSimilarSongs')->name('similarSongs');
Route::post('/lengthPlaylist','PublicController@lengthOfPlaylist')->name('lengthOfPlaylist');
