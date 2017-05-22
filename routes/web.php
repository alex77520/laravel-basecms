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

    Route::get('/login' , function(){
        return view('web/v1/login');
    })->name('/login');
    Route::post('/login' , 'web\AuthController@login')->name('/login');
    Route::post('/autologin' , 'web\AuthController@autoLogin')->name('/autologin');

    Route::get('/logout' , 'web\AuthController@logout')->name('/logout');

    Route::get('/regist' , function(){
        return view('web/v1/regist');
    })->name('/regist');
    Route::post('/regist' , 'web\AuthController@regist')->name('/regist');

Route::group(['middleware'=>'checkLogin','prefix'=>'admin'], function(){
    #
    # 错误信息处理 
    # ['405'=>'权限不足']
    #
    Route::get('/405' , 'web\CommonController@error')->name('/admin/405');
    Route::get('/modal/405' , 'web\CommonController@error_modal')->name('/admin/modal/405');


    Route::get('/','web\DashBoardController@index')->name('/admin');
    Route::get('/user','web\UserController@index')->name('/admin/user')->middleware('checkRole:UserSee');

    Route::get('/user/edit','web\UserController@edit')->name('/admin/user/edit')->middleware('checkRole:UserEdit');
    Route::post('/user/edit','web\UserController@doEdit')->name('/admin/user/edit')->middleware('checkRole:UserEdit');
    
    # 机构管理
    Route::get('/group', 'web\GroupController@index')->name('/admin/group')->middleware('checkRole:GroupSee');
    Route::get('/group/edit', 'web\GroupController@edit')->name('/admin/group/edit')->middleware('checkRole:GroupEdit');
    Route::post('/group/edit', 'web\GroupController@doEdit')->name('/admin/group/edit')->middleware('checkRole:GroupEdit');
    Route::get('/group/setting/limit', 'web\GroupController@limitSetting')->name('/admin/group/setting/limit')->middleware('checkRole:GroupSettingLimit');
    Route::post('/group/setting/limit', 'web\GroupController@doLimitSetting')->name('/admin/group/setting/limit')->middleware('checkRole:GroupSettingLimit');
    Route::get('/group/setting/size', 'web\GroupController@sizeSetting')->name('/admin/group/setting/size')->middleware('checkRole:GroupSettingSize');
    Route::post('/group/setting/size', 'web\GroupController@doSizeSetting')->name('/admin/group/setting/size')->middleware('checkRole:GroupSettingSize');
    Route::post('/group/setting/enable', 'web\GroupController@doEnable')->name('/admin/group/setting/enable')->middleware('checkRole:GroupSettingEnable');
    Route::post('/group/setting/recommend', 'web\GroupController@doRecommend')->name('/admin/group/setting/recommend')->middleware('checkRole:GroupSettingRecommend');

    # 用户角色管理
    Route::get('/role','web\UserController@role')->name('/admin/role')->middleware('checkRole:RoleSee');
    Route::get('/role/add','web\UserController@roleAdd')->name('/admin/role/add')->middleware('checkRole:RoleAdd,modal');
    Route::post('/role/add','web\UserController@doRoleAdd')->name('/admin/role/add')->middleware('checkRole:RoleAdd');
    Route::get('/role/edit','web\UserController@roleEdit')->name('/admin/role/edit')->middleware('checkRole:RoleEdit,modal');
    Route::post('/role/edit','web\UserController@doRoleEdit')->name('/admin/role/edit')->middleware('checkRole:RoleEdit');
    Route::post('/role/del','web\UserController@doRoleDel')->name('/admin/role/del')->middleware('checkRole:RoleDel');

    # 文章管理
    Route::get('/posts','web\PostsController@index')->name('/admin/posts')->middleware('checkRole:PostSee');
    Route::get('/posts/intro','web\PostsController@intro')->name('/admin/posts/intro')->middleware('checkRole:PostSee');
    Route::post('/posts/delete','web\PostsController@doDelPost')->name('/admin/posts/delete')->middleware('checkRole:PostDel');
    Route::post('/posts/deletes','web\PostsController@doDelPosts')->name('/admin/posts/deletes')->middleware('checkRole:PostDels');
    Route::post('/posts/unban','web\PostsController@doUnbanPost')->name('/admin/posts/unban')->middleware('checkRole:PostBan');
    Route::post('/posts/unbans','web\PostsController@doUnbanPosts')->name('/admin/posts/unbans')->middleware('checkRole:PostBans');
    Route::post('/posts/ban','web\PostsController@doBanPost')->name('/admin/posts/ban')->middleware('checkRole:PostBan');
    Route::post('/posts/bans','web\PostsController@doBanPosts')->name('/admin/posts/bans')->middleware('checkRole:PostBans');
    Route::post('/posts/enable','web\PostsController@doEnablePost')->name('/admin/posts/enable')->middleware('checkRole:PostDisable');
    Route::post('/posts/enables','web\PostsController@doEnablePosts')->name('/admin/posts/enables')->middleware('checkRole:PostDisables');
    Route::post('/posts/disable','web\PostsController@doDisablePost')->name('/admin/posts/disable')->middleware('checkRole:PostDisable');
    Route::post('/posts/disables','web\PostsController@doDisablePosts')->name('/admin/posts/disables')->middleware('checkRole:PostDisables');
    Route::post('/posts/restore','web\PostsController@doRestore')->name('/admin/posts/restore')->middleware('checkRole:PostRestore');
    Route::post('/posts/restores','web\PostsController@doRestores')->name('/admin/posts/restores')->middleware('checkRole:PostRestores');
    Route::post('/posts/up','web\PostsController@doUp')->name('/admin/posts/up')->middleware('checkRole:PostUp');
    
    # 站内信
    Route::get('/notice', 'web\MsgController@index')->name('/admin/notice')->middleware('checkRole:MsgSee');
    Route::get('/notice/create', 'web\MsgController@create')->name('/admin/notice/create')->middleware('checkRole:MsgEdit');
    Route::post('/notice/create', 'web\MsgController@doCreate')->name('/admin/notice/create')->middleware('checkRole:MsgEdit');
    Route::post('/notice/create/group', 'web\MsgController@doCreateGroup')->name('/admin/notice/create/group')->middleware('checkRole:MsgEdit');
    Route::post('/notice/create/user', 'web\MsgController@doCreateUser')->name('/admin/notice/create/user')->middleware('checkRole:MsgEdit');
    Route::post('/notice/delete', 'web\MsgController@doDelNotice')->name('/admin/notice/delete')->middleware('checkRole:MsgDel');
    Route::post('/notice/deletes', 'web\MsgController@doDelNotices')->name('/admin/notice/deletes')->middleware('checkRole:MsgDels');
    Route::get('/notice/intro', 'web\MsgController@intro')->name('/admin/notice/intro')->middleware('checkRole:MsgSee');
    Route::get('/notice/intro/users', 'web\MsgController@intro_users')->name('/admin/notice/intro/users')->middleware('checkRole:MsgSee');
    Route::post('/notice/dashboard' ,'web\MsgController@getUnreadMsg')->name('/admin/notice/dashboard');

    # 文件管理
    Route::get('/resource','web\ResourceController@index')->name('/admin/resource');
    Route::get('/resource/upload','web\ResourceController@upload')->name('/admin/resource/upload');
    Route::post('/resource/upload','web\ResourceController@saveFileInfo')->name('/admin/resource/upload');
    Route::post('/resource/edit','web\ResourceController@doEdit')->name('/admin/resource/edit');
    Route::get('/resource/edit','web\ResourceController@edit')->name('/admin/resource/edit');
    Route::post('/resource/del','web\ResourceController@del')->name('/admin/resource/del');
    Route::get('/resource/dialog', 'web\ResourceController@chooseModal')->name('/admin/resource/dialog');
    Route::post('/resource/get', 'web\ResourceController@getFiles')->name('/admin/resource/get');
    Route::post('/resource/img/get', 'web\ResourceController@getImages')->name('/admin/resource/img/get');

    # 文件管理的分类
    Route::get('/resource/classify/add','web\ResourceController@classifyAdd')->name('/admin/resource/classify/add');
    Route::post('/resource/classify/add','web\ResourceController@doClassifyAdd')->name('/admin/resource/classify/add');
    Route::get('/resource/classify/edit','web\ResourceController@classifyEdit')->name('/admin/resource/classify/edit');
    Route::post('/resource/classify/edit','web\ResourceController@doClassifyEdit')->name('/admin/resource/classify/edit');
    Route::post('/resource/classify/del','web\ResourceController@doClassifyDel')->name('/admin/resource/classify/del');

    # 资源管理
    Route::get('/posts/classify','web\PostsController@classify')->name('/admin/posts/classify')->middleware('checkRole:PostClassifySee');
    Route::get('/posts/classify/add','web\PostsController@classifyAdd')->name('/admin/posts/classify/add')->middleware('checkRole:PostClassifyEdit,modal');
    Route::post('/posts/classify/add','web\PostsController@doClassifyAdd')->name('/admin/posts/classify/add')->middleware('checkRole:PostClassifyEdit');
    Route::get('/posts/classify/edit','web\PostsController@classifyEdit')->name('/admin/posts/classify/edit')->middleware('checkRole:PostClassifyEdit,modal');
    Route::post('/posts/classify/edit','web\PostsController@doClassifyEdit')->name('/admin/posts/classify/edit')->middleware('checkRole:PostClassifyEdit');
    Route::post('/posts/classify/del','web\PostsController@doClassifyDel')->name('/admin/posts/classify/del')->middleware('checkRole:PostClassifyDelete');
    
    # 用户中心
    Route::get('/ucenter','web\UcenterController@index')->name('/admin/ucenter');
    Route::get('/ucenter/userinfo/edit','web\UcenterController@userInfoEdit')->name('/admin/ucenter/userinfo/edit');
    Route::post('/ucenter/userinfo/edit','web\UcenterController@doUserInfoEdit')->name('/admin/ucenter/userinfo/edit');
    Route::get('/ucenter/password','web\UcenterController@password')->name('/admin/ucenter/password');
    Route::post('/ucenter/password','web\UcenterController@doPassword')->name('/admin/ucenter/password');

    # 机构信息维护
    Route::group(['middleware' => 'checkGroup'], function() {
        Route::get('/organize', 'web\OrganizeController@index')->name('/admin/organize');
        Route::post('/organize/edit', 'web\OrganizeController@doEdit')->name('/admin/organize/edit');
        Route::get('/organize/user', 'web\OrganizeController@user')->name('/admin/organize/user');
        Route::get('/organize/user/add', 'web\OrganizeController@userAdd')->name('/admin/organize/user/add');
        Route::post('/organize/user/add', 'web\OrganizeController@doUserAdd')->name('/admin/organize/user/add');
        Route::get('/organize/user/password', 'web\OrganizeController@password')->name('/admin/organize/user/password');
        Route::post('/organize/user/password', 'web\OrganizeController@doPassword')->name('/admin/organize/user/password');
        Route::post('/organize/user/delete', 'web\OrganizeController@doUserDel')->name('/admin/organize/user/delete');
        Route::post('/organize/user/enable', 'web\OrganizeController@doEnable')->name('/admin/organize/user/enable');
        Route::get('/organize/log', 'web\OrganizeController@log')->name('/admin/organize/log');
        Route::post('/organize/log/delete', 'web\OrganizeController@doLogDelete')->name('/admin/organize/log/delete');
        Route::post('/organize/log/deletes', 'web\OrganizeController@doLogDeletes')->name('/admin/organize/log/deletes');
        Route::get('/organize/notice', 'web\MsgController@organIndex')->name('/admin/organize/notice');
        Route::get('/organize/notice/create', 'web\MsgController@organCreate')->name('/admin/organize/notice/create');
        Route::post('/organize/notice/create', 'web\MsgController@doOrganCreate')->name('/admin/organize/notice/create');
        Route::post('/organize/notice/create/user', 'web\MsgController@doOrganCreateUser')->name('/admin/organize/notice/create/user');
        Route::get('/organize/notice/intro', 'web\MsgController@organIntro')->name('/admin/organize/notice/intro');
        Route::get('/organize/notice/intro/users', 'web\MsgController@organIntroUsers')->name('/admin/organize/notice/intro/users');
        Route::post('/organize/notice/delete', 'web\MsgController@doOrganDelNotice')->name('/admin/organize/notice/delete');
        Route::post('/organize/notice/deletes', 'web\MsgController@doOrganDelNotices')->name('/admin/organize/notice/deletes');
    });

    # 文章管理
    Route::group(['middleware' => 'checkGroupStatus'], function() {
        Route::get('/article','web\ArticleController@index')->name('/admin/article');
        Route::get('/article/choose','web\ArticleController@choose')->name('/admin/article/choose');
        Route::get('/article/intro','web\ArticleController@intro')->name('/admin/article/intro');
        Route::post('/article/delete','web\ArticleController@doDelPost')->name('/admin/article/delete');
        Route::post('/article/deletes','web\ArticleController@doDelPosts')->name('/admin/article/deletes');
        Route::post('/article/enables','web\ArticleController@doEnablePosts')->name('/admin/article/enables');
        Route::post('/article/disables','web\ArticleController@doDisablePosts')->name('/admin/article/disables');
        Route::post('/article/enable','web\ArticleController@doEnablePost')->name('/admin/article/enable');
        Route::post('/article/disable','web\ArticleController@doDisablePost')->name('/admin/article/disable');
        # 多模式[添加]
        Route::get('/article/create/single','web\ArticleController@single')->name('/admin/article/create/single');
        Route::post('/article/create/single','web\ArticleController@doSingle')->name('/admin/article/create/single');
        Route::get('/article/create/image-text','web\ArticleController@imgText')->name('/admin/article/create/image-text');
        Route::post('/article/create/image-text','web\ArticleController@doImgText')->name('/admin/article/create/image-text');
        Route::get('/article/create/markdown','web\ArticleController@markdown')->name('/admin/article/create/markdown');
        Route::post('/article/create/markdown','web\ArticleController@doMarkdown')->name('/admin/article/create/markdown');
        # 多模式[修改]
        Route::get('/article/edit/single','web\ArticleController@editSingle')->name('/admin/article/edit/single');
        Route::post('/article/edit/single','web\ArticleController@doEditSingle')->name('/admin/article/edit/single');
        Route::get('/article/edit/image-text','web\ArticleController@editImgText')->name('/admin/article/edit/image-text');
        Route::post('/article/edit/image-text','web\ArticleController@doEditImgText')->name('/admin/article/edit/image-text');
        Route::get('/article/edit/markdown','web\ArticleController@editMarkdown')->name('/admin/article/edit/markdown');
        Route::post('/article/edit/markdown','web\ArticleController@doEditMarkdown')->name('/admin/article/edit/markdown');
        # 图文模式 > 条目
        Route::get('/article/image-text/item', 'web\ArticleController@ImgTextItem')->name('/admin/article/image-text/item');
        Route::post('/article/image-text/item/list', 'web\ArticleController@getImgTextItems')->name('/admin/article/image-text/item/list');
        Route::post('/article/image-text/item/get', 'web\ArticleController@getImgTextItem')->name('/admin/article/image-text/item/get');
        Route::post('/article/image-text/item/add', 'web\ArticleController@doAddImgTextItem')->name('/admin/article/image-text/item/add');
        Route::post('/article/image-text/item/edit', 'web\ArticleController@doEditImgTextItem')->name('/admin/article/image-text/item/edit');
        Route::post('/article/image-text/item/del', 'web\ArticleController@doDelImgTextItem')->name('/admin/article/image-text/item/del');
    });

    Route::get('/msg','web\MsgsController@index')->name('/admin/msg');
    Route::get('/msg/intro','web\MsgsController@intro')->name('/admin/msg/intro');
    Route::post('/msg/deletes','web\MsgsController@doDels')->name('/admin/msg/deletes');
    Route::post('/msg/reads','web\MsgsController@doReads')->name('/admin/msg/reads');
});
