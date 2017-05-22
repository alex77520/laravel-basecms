<?php

namespace App\Http\Middleware;

use App\Http\Controllers\web\Controller;
use Closure;

class checkGroupStatus
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
        if(!$Ctrl->groupStatus()){
            if($request->ajax()){
                return $Ctrl->ajaxError('当前机构权限被冻结，请联系管理员处理');
            }
            return redirect()->route('/admin/405',['msg'=>'当前机构权限被冻结，请联系管理员处理']);
        }
        return $next($request);
    }
}
