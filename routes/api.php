<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/auth', 'api\AuthController@index');
Route::get('/post', 'api\PostController@post_list');
Route::get('/post/classify', 'api\PostController@post_classify_list');
Route::get('/post/content', 'api\PostController@post_content');
Route::get('/classify', 'api\PostClassifyController@classify_list');
