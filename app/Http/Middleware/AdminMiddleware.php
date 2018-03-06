<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Administrator;
use Auth, Response;

class AdminMiddleware
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if(!Auth::guard('admin')->check()) // 未登录
        {
            return redirect('/admin/login');
//            $return["status"] = false;
//            $return["log"] = "admin-no-login";
//            $return["msg"] = "请先登录";
//            return Response::json($return);
        }
        else
        {
            $admin = Auth::guard('admin')->user();
            view()->share('admin', $admin);
        }
        return $next($request);

    }
}
