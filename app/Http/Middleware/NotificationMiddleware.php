<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Administrator;
use App\Models\Notification;
use Auth, Response;

class NotificationMiddleware
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        // 执行动作
        $user = Auth::user();
        $count = Notification::where(['is_read'=>0,'type'=>8,'user_id'=>$user->id])->count();
        if(!$count) $count = '';
        view()->share('notification_count', $count);

        return $next($request);
    }
}
