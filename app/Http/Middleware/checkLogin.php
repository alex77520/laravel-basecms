<?php

namespace App\Http\Middleware;

use App\Http\Controllers\web\Controller;
use Illuminate\Support\Facades\Session;
use Closure;
use URL;

class checkLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $Controller = new Controller;
        if(!$request->session()->has('ticket')){
            if($request->ajax()){
                return $Controller->ajaxError('当前登录状态失效，请重新登录');
            }
            Session::put('login_return', URL::full()); # 存储当前操作，
            return redirect('/login');
        }else{
            $token = $request->session()->get('ticket');
            $userInfo = $Controller->getWebUserInfo($token);
            if(!$userInfo){
                Session::put('login_return', URL::full());
                return redirect('/login');
            }else{
                return $next($request);
            }
        }
    }
}
