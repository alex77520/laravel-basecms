<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Clients;
use App\Http\Controllers\api\Controller;

class ApiBase
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
        $Ctrl = new Controller;
        if(!$request->header('appid')){
            return $Ctrl->respError('1001');
        }
        $where = [
            'client_id' => $request->header('appid')
        ];
        if(!Clients::where($where)->first()){
            return $Ctrl->respError('1002');
        }
        return $next($request);
    }
}
