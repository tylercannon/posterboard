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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//board
Route::get('/board', 'BoardController@index')->name('board');

//profile
Route::get('/{username}', 'ProfileController@index');
Route::get('/{username}/followers', 'ProfileController@followers');
Route::get('/{username}/following', 'ProfileController@following');
Route::get('/{username}/favorites', 'ProfileController@favorites');
Route::get('/{username}/edit', 'ProfileController@edit');
Route::post('/{username}/update', 'ProfileController@update')->name('update');
Route::post('/followUser', 'ProfileController@followUser')->name('follow');
Route::post('/unfollowUser', 'ProfileController@unfollowUser')->name('unfollow');

//posts
Route::post('/createPost', 'PostController@post')->name('createPost');
Route::post('/repost', 'PostController@repost')->name('repost');
Route::post('/favorite', 'PostController@favorite')->name('favorite');
Route::post('/unrepost', 'PostController@unrepost')->name('unrepost');
Route::post('/unfavorite', 'PostController@unfavorite')->name('unfavorite');

//search
Route::get('/search/{searchStr}', function ($searchStr) {
    $searchVal = '%' . $searchStr . '%';
    return DB::table('users')
        ->where('users.name', 'like', $searchVal)
        ->orWhere('users.username', 'like', $searchVal)
        ->select('users.id', 'users.name', 'users.username')
        ->get();
});