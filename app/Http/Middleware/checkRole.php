<?php

namespace App\Http\Middleware;

use App\Http\Controllers\web\Controller;
use App\Http\Controllers\Controller as BaseController;
use Closure;

class checkRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $limit, $method = null)
    {
        $Ctrl = new Controller;
        if(!$Ctrl->HasLimit($limit)){
            if($method == 'modal'){
                return redirect()->route('/admin/modal/405');
            }else{
                if($request->ajax()){
                    return $Ctrl->ajaxError('资源不存在或权限不够');
                }
            }
            return redirect()->route('/admin/405');
        }
        return $next($request);
    }
}
