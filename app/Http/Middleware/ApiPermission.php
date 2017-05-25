<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\api\Controller;

class ApiPermission
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
        if(!$request->header('token')){
            return Controller::respError('401', '登录凭证缺失');
        }
        if(Controller::$data['user'] === null){
            return Controller::respError('401', '登录凭证无效');
        }
        return $next($request);
    }
}
