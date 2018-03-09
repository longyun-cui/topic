<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pivot_User_Topic extends Model
{
    //
    protected $table = "pivot_user_topic";
    protected $fillable = [
        'sort', 'type', 'user_id', 'topic_id'
    ];
    protected $dateFormat = 'U';


    // 用户
    function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    // 话题
    function topic()
    {
        return $this->belongsTo('App\Models\Topic','topic_id','id');
    }





}
