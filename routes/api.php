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

Route::group(['middleware'=>'api.base'], function(){
    Route::post('/regist', 'api\AuthController@regist'); // 注册
    Route::post('/access_token', 'api\AuthController@login'); // 获取登录凭证
    Route::post('/refresh_token', 'api\AuthController@refreshToken'); // 刷新令牌，需要Token

    Route::post('/account/bind', 'api\AuthController@bindAccount'); // 根据用户信息绑定第三方
    Route::post('/account/regist', 'api\AuthController@registAccount'); // 根据第三方信息注册新账号
    Route::post('/common/sms/regist', 'api\CommonController@registSMS'); // 注册的短信
    
    
    Route::get('/post', 'api\PostController@post_list');
    Route::get('/post/classify', 'api\PostController@post_classify_list');
    Route::get('/post/content', 'api\PostController@post_content');
    Route::get('/classify', 'api\PostClassifyController@classify_list');


    Route::group(['middleware'=>'api.permission'], function(){

    });
});