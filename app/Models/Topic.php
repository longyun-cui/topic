<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    //
    protected $table = "topic";
    protected $fillable = [
        'sort', 'type', 'active', 'user_id', 'is_anonymous', 'title', 'positive', 'negative', 'description', 'content', 'cover_pic',
        'is_shared', 'visit_num', 'share_num'
    ];
    protected $dateFormat = 'U';
//    protected $dates = ['created_at', 'updated_at', 'disabled_at'];


    public function getDates()
    {
        return array('created_at','updated_at');
//        return array(); // 原形返回；
    }


    // 用户信息
    function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    // 评论
    function communications()
    {
        return $this->hasMany('App\Models\Communication','topic_id','id');
    }

    // 点赞
    function favor()
    {
        return $this->hasMany('App\Models\Communication','topic_id','id');
    }

    // 收藏
    function collections()
    {
        return $this->hasMany('App\Models\Pivot_User_Collection','topic_id','id');
    }

    // 内容
    function others()
    {
        return $this->hasMany('App\Models\Pivot_User_Topic','topic_id','id');
    }

    // 与我相关的话题
    function pivot_users()
    {
        return $this->belongsToMany('App\User','pivot_user_topic','topic_id','user_id');
    }

    /**
     * 获得此人的所有标签。
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }




}
