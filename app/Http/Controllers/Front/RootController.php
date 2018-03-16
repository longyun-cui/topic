<?php

namespace App\Http\Controllers\Front;

use function foo\func;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Front\RootRepository;


class RootController extends Controller
{
    //
    private $repo;
    public function __construct()
    {
        $this->repo = new RootRepository;
    }


    public function view_all()
    {
        return $this->repo->view_all(request()->all());
    }

    public function view_anonymous()
    {
        return $this->repo->view_anonymous(request()->all());
    }

    public function view_debates()
    {
        return $this->repo->view_debates(request()->all());
    }

    public function view_topic($id=0)
    {
        return $this->repo->view_topic(request()->all(),$id);
    }

    public function view_user($id=0)
    {
        return $this->repo->view_user(request()->all(),$id);
    }


    // 收藏
    public function topic_collect_save()
    {
        return $this->repo->topic_collect_save(request()->all());
    }
    public function topic_collect_cancel()
    {
        return $this->repo->topic_collect_cancel(request()->all());
    }



    // 点赞
    public function topic_favor_save()
    {
        return $this->repo->topic_favor_save(request()->all());
    }
    public function topic_favor_cancel()
    {
        return $this->repo->topic_favor_cancel(request()->all());
    }



    // 评论
    public function item_comment_save()
    {
        return $this->repo->item_comment_save(request()->all());
    }
    public function item_comment_get()
    {
        return $this->repo->item_comment_get(request()->all());
    }



    // 回复
    public function item_reply_save()
    {
        return $this->repo->item_reply_save(request()->all());
    }
    public function item_reply_get()
    {
        return $this->repo->item_reply_get(request()->all());
    }



    // 评论点赞
    public function item_comment_favor_save()
    {
        return $this->repo->item_comment_favor_save(request()->all());
    }
    public function item_comment_favor_cancel()
    {
        return $this->repo->item_comment_favor_cancel(request()->all());
    }





}
