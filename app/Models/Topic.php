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


    // 管理员
    function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    // 内容
    function communications()
    {
        return $this->hasMany('App\Models\Communication','topic_id','id');
    }

    /**
     * 获得此人的所有标签。
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }

    // 与我相关的话题
    function pivot_users()
    {
        return $this->belongsToMany('App\User','pivot_user_topic','topic_id','user_id');
    }




}
