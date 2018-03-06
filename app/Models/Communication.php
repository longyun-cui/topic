<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    //
    protected $table = "communications";
    protected $fillable = [
        'sort', 'type', 'active', 'user_id', 'topic_id', 'is_anonymous', 'reply_id', 'dialog_id', 'order', 'title', 'content',
        'is_shared', 'visit_num', 'share_num'
    ];
    protected $dateFormat = 'U';


    // 管理员
    function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    // 课程
    function topic()
    {
        return $this->belongsTo('App\Models\Topic','course_id','id');
    }

    // 父节点
    function reply()
    {
        return $this->belongsTo('App\Models\Communication','reply_id','id');
    }

    // 子节点
    function children()
    {
        return $this->hasMany('App\Models\Communication','reply_id','id');
    }

    /**
     * 获得此人的所有标签。
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }




}
