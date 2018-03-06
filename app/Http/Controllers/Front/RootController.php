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

    public function topic_comment_save()
    {
        return $this->repo->topic_comment_save(request()->all());
    }

    public function topic_comment_get()
    {
        return $this->repo->topic_comment_get(request()->all());
    }





}
