<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'moblie', 'email', 'name', 'anonymous_name', 'nickname', 'truename', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dateFormat = 'U';


    // 我的话题
    function topics()
    {
        return $this->hasMany('App\Models\Topic','user_id','id');
    }

    // 收藏的话题
    function collections()
    {
        return $this->belongsToMany('App\Models\Topic','pivot_user_collection','user_id','topic_id');
    }

    // 与我相关的话题
    function pivot_topics()
    {
        return $this->belongsToMany('App\Models\Topic','pivot_user_topic','user_id','topic_id');
    }



}
