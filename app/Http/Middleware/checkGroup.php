<?php

namespace App\Http\Middleware;

use App\Http\Controllers\web\Controller;
use Closure;

class checkGroup
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
        if(!$Ctrl->isGroupAdmin()){
            return redirect()->route('/admin/405');
        }
        return $next($request);
    }
}
