<?php
namespace App\Repositories\Front;

use App\User;
use App\Models\Topic;
use App\Models\Communication;
use App\Models\Pivot_User_Topic;
use App\Models\Pivot_User_Collection;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception, Blade;
use QrCode;
use function Sodium\increment;

class RootRepository {

    private $model;
    public function __construct()
    {
        Blade::setEchoFormat('%s');
        Blade::setEchoFormat('e(%s)');
        Blade::setEchoFormat('nl2br(e(%s))');
    }



    // 平台主页
    public function view_item_html($id)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $data = Topic::with([
                'user',
                'communications'=>function($query) { $query->with(['user'])->limit(10)->orderBy('id','desc'); },
                'others'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); }
            ])->find($id);
        }
        else
        {
            $datas = Topic::with([
                'user',
                'communications'=>function($query) { $query->with(['user'])->limit(10)->orderBy('id','desc'); }
            ])->find($id);
        }
//        dd($datas->toArray());
        return view('frontend.component.topic')->with(['getType'=>'items','data'=>$data])->__toString();
    }


    // 平台主页 【全部】
    public function view_all($post_data)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $datas = Topic::with([
                'user',
                'communications'=>function($query) { $query->with(['user'])->limit(10)->orderBy('id','desc'); },
                'collections'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); },
                'others'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); }
            ])->where('active', 1)
                ->orderBy('id','desc')->paginate(20);
        }
        else
        {
            $datas = Topic::with([
                'user',
                'communications'=>function($query) { $query->with(['user'])->limit(10)->orderBy('id','desc'); }
            ])->where('active', 1)
                ->orderBy('id','desc')->paginate(20);
        }
//        dd($datas->toArray());
        return view('frontend.root.topics')->with(['getType'=>'items','datas'=>$datas,'menu_all'=>'active']);
    }


    // 平台主页 【辩题】
    public function view_debates($post_data)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $datas = Topic::with([
                'user',
                'communications'=>function($query) { $query->with(['user'])->limit(10)->orderBy('id','desc'); },
                'collections'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); },
                'others'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); }
            ])->where(['active'=>1,'type'=>2])
                ->orderBy('id','desc')->paginate(20);
        }
        else
        {
            $datas = Topic::with([
                'user',
                'communications'=>function($query) { $query->with(['user'])->limit(10)->orderBy('id','desc'); }
            ])->where(['active'=>1,'type'=>2])
                ->orderBy('id','desc')->paginate(20);
        }
        return view('frontend.root.topics')->with(['getType'=>'items','datas'=>$datas,'menu_debates'=>'active']);
    }


    // 平台主页 【匿名话题】
    public function view_anonymous($post_data)
    {

        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $datas = Topic::with([
                'user',
                'communications'=>function($query) { $query->with(['user'])->limit(10)->orderBy('id','desc'); },
                'collections'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); },
                'others'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); }
            ])->where(['active'=>1,'is_anonymous'=>1])
                ->orderBy('id','desc')->paginate(20);
        }
        else
        {
            $datas = Topic::with([
                'user',
                'communications'=>function($query) { $query->with(['user'])->limit(10)->orderBy('id','desc'); }
            ])->where(['active'=>1,'is_anonymous'=>1])
                ->orderBy('id','desc')->paginate(20);
        }
        return view('frontend.root.topics')->with(['getType'=>'items','datas'=>$datas,'menu_anonymous'=>'active']);
    }


    // 平台主页 【用户首页】
    public function view_user($post_data,$id=0)
    {
//        $course_encode = $post_data['id'];
        $user_encode = $id;
        $user_decode = decode($user_encode);
        if(!$user_decode) return view('frontend.404');

        $user = User::find($user_decode);

        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $datas = Topic::with([
                'user',
                'communications'=>function($query) { $query->with(['user'])->limit(10)->orderBy('id','desc'); },
                'collections'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); },
                'others'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); }
            ])->where(['user_id'=>$user_decode,'active'=>1,'is_anonymous'=>0])
                ->orderBy('id','desc')->paginate(20);
        }
        else
        {
            $datas = Topic::with([
                'user',
                'communications'=>function($query) { $query->with(['user'])->limit(10)->orderBy('id','desc'); }
            ])->where(['user_id'=>$user_decode,'active'=>1,'is_anonymous'=>0])
                ->orderBy('id','desc')->paginate(20);
        }

        return view('frontend.root.user')->with(['getType'=>'items','data'=>$user,'topics'=>$datas]);
    }


    // 课程详情
    public function view_topic($post_data,$id=0)
    {
//        $topic_encode = $post_data['id'];
        $topic_encode = $id;
        $topic_decode = decode($topic_encode);
        if(!$topic_decode) return view('frontend.404');


        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $topic = Topic::with([
                'user',
//                'communications'=>function($query) { $query->with(['user'])->limit(10)->orderBy('id','desc'); },
                'collections'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); },
                'others'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); }
            ])->find($topic_decode);
        }
        else
        {
            $topic = Topic::with([
                'user'//,
//            'communications'=>function($query) { $query->with(['user'])->limit(10)->orderBy('id','desc'); }
            ])->find($topic_decode);
        }

        $communications = Communication::with(['user'])->where('topic_id',$topic_decode)
            ->orderBy('id','desc')->paginate(20);

        $topic->encode_id = encode($topic->id);
        $topic->user->encode_id = encode($topic->user->id);

        return view('frontend.root.topic')->with(['getType'=>'item','data'=>$topic,'communications'=>$communications]);
    }




    // 收藏
    public function topic_collect_save($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'topic_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'topic_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $topic_encode = $post_data['topic_id'];
            $topic_decode = decode($topic_encode);
            if(!$topic_decode) return response_error([],"参数有误，请重试！");

            $topic = Topic::find($topic_decode);
            if($topic)
            {
                DB::beginTransaction();
                try
                {
                    $time = time();
                    $user = Auth::user();
                    $user->collections()->attach($topic_decode,['type'=>1,'created_at'=>$time,'updated_at'=>$time]);

                    $topic->increment('collect_num');

                    $return = [];
//                    $return['html'] = $this->view_item_html($topic_decode);

                    DB::commit();
                    return response_success($return);
                }
                catch (Exception $e)
                {
                    DB::rollback();
//                    exit($e->getMessage());
//                    $msg = $e->getMessage();
                    $msg = '添加失败，请重试！';
                    return response_fail([], $msg);
                }
            }
            else return response_error([],"该话题不存在，刷新一下试试！");


        }
        else return response_error([],"请先登录！");

    }
    // 取消收藏
    public function topic_collect_cancel($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'topic_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'topic_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $topic_encode = $post_data['topic_id'];
            $topic_decode = decode($topic_encode);
            if(!$topic_decode) return response_error([],"该话题不存在，刷新一下试试！");

            $topic = Topic::find($topic_decode);
            if($topic)
            {
                DB::beginTransaction();
                try
                {
                    $user = Auth::user();
                    $user_id = $user->id;

                    $collections = Pivot_User_Collection::where(['user_id'=>$user_id,'topic_id'=>$topic_decode]);
                    $count = count($collections->get());
                    if($count)
                    {
                        $num = $collections->delete();
                        if($num != $count) throw new Exception("delete--pivot--fail");

                        $topic->decrement('collect_num');
                    }

                    $return = [];
//                    $return['html'] = $this->view_item_html($topic_decode);

                    DB::commit();
                    return response_success($return);
                }
                catch (Exception $e)
                {
                    DB::rollback();
//                    exit($e->getMessage());
//                    $msg = $e->getMessage();
                    $msg = '操作失败，请重试！';
                    return response_fail([], $msg);
                }
            }
            else return response_error([],"该话题不存在，刷新一下试试！");

        }
        else return response_error([],"请先登录！");

    }


    // 点赞
    public function topic_favor_save($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'topic_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'topic_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $topic_encode = $post_data['topic_id'];
            $topic_decode = decode($topic_encode);
            if(!$topic_decode) return response_error([],"参数有误，请重试！");

            $topic = Topic::find($topic_decode);
            if($topic)
            {
                DB::beginTransaction();
                try
                {
                    $time = time();
                    $user = Auth::user();
                    $user->pivot_topics()->attach($topic_decode,['type'=>1,'created_at'=>$time,'updated_at'=>$time]);

                    $topic->increment('favor_num');

                    $return = [];
//                    $return['html'] = $this->view_item_html($topic_decode);

                    DB::commit();
                    return response_success($return);
                }
                catch (Exception $e)
                {
                    DB::rollback();
//                    exit($e->getMessage());
//                    $msg = $e->getMessage();
                    $msg = '添加失败，请重试！';
                    return response_fail([], $msg);
                }
            }
            else return response_error([],"该话题不存在，刷新一下试试！");


        }
        else return response_error([],"请先登录！");

    }
    // 取消赞
    public function topic_favor_cancel($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'topic_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'topic_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $topic_encode = $post_data['topic_id'];
            $topic_decode = decode($topic_encode);
            if(!$topic_decode) return response_error([],"该话题不存在，刷新一下试试！");

            $topic = Topic::find($topic_decode);
            if($topic)
            {
                DB::beginTransaction();
                try
                {
                    $user = Auth::user();
                    $user_id = $user->id;

                    $favors = Pivot_User_Topic::where(['type'=>1,'user_id'=>$user_id,'topic_id'=>$topic_decode]);
                    $count = count($favors->get());
                    if($count)
                    {
                        $num = $favors->delete();
                        if($num != $count) throw new Exception("delete--pivot--fail");

                        $topic->decrement('favor_num');
                    }

                    $return = [];
//                    $return['html'] = $this->view_item_html($topic_decode);

                    DB::commit();
                    return response_success($return);
                }
                catch (Exception $e)
                {
                    DB::rollback();
//                    exit($e->getMessage());
//                    $msg = $e->getMessage();
                    $msg = '操作失败，请重试！';
                    return response_fail([], $msg);
                }
            }
            else return response_error([],"该话题不存在，刷新一下试试！");

        }
        else return response_error([],"请先登录！");

    }



    // 用户评论
    public function topic_comment_save($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'topic_id.required' => '参数有误',
            'content.required' => '内容不能为空',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'topic_id' => 'required',
            'content' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if($post_data['type'] == 2)
        {
            $messages2 = [
                'positive.required' => '请输入正方观点',
                'negative.required' => '请输入反方观点',
            ];
            $v2 = Validator::make($post_data, [
                'positive' => 'required',
                'negative' => 'required'
            ], $messages2);
            if ($v2->fails())
            {
                $errors2 = $v2->errors();
                return response_error([],$errors2->first());
            }
        }

        if(Auth::check())
        {
            $topic_encode = $post_data['topic_id'];
            $topic_decode = decode($topic_encode);
            if(!$topic_decode) return response_error([],"该话题不存在，刷新一下试试！");

            $user = Auth::user();
            $insert['type'] = $post_data['type'];
            $insert['support'] = empty($post_data['support']) ? 0 : $post_data['support'];
            $insert['user_id'] = $user->id;
            $insert['topic_id'] = $topic_decode;
            $insert['content'] = $post_data['content'];
            if(!empty($post_data['is_anonymous']) && $post_data['is_anonymous'] == 'on') $insert['is_anonymous'] = 1;

            DB::beginTransaction();
            try
            {
                $topic = Topic::find($topic_decode);
                $topic->timestamps = false;
                $topic->increment('comment_num');

                $communication = new Communication;
                $bool = $communication->fill($insert)->save();
                if(!$bool) throw new Exception("insert--communication--fail");

                $html["html"] = view('frontend.component.comment')->with("comment",$communication)->__toString();

                DB::commit();
                return response_success($html);
            }
            catch (Exception $e)
            {
                DB::rollback();
//            exit($e->getMessage());
//            $msg = $e->getMessage();
                $msg = '添加失败，请重试！';
                return response_fail([], $msg);
            }
        }
        else return response_error([],"请先登录！");

    }

    public function topic_comment_get($post_data)
    {
        $topic_encode = $post_data['id'];
        $topic_decode = decode($topic_encode);

        $getSort = $post_data['getSort'];
        $comments = Communication::with(['user'])->where('topic_id',$topic_decode);

        if($getSort == "positive") $comments->where('support',1);
        else if($getSort == "negative") $comments->where('support',2);

        if(!empty($post_data['min_id']) && $post_data['min_id'] != 0) $comments->where('id', '<', $post_data['min_id']);

        $comments = $comments->orderBy('id','desc')->paginate(10);

        if(!$comments->isEmpty())
        {
            $return["html"] = view('frontend.component.comments')->with("comments",$comments)->__toString();
            $return["max_id"] = $comments->first()->id;
            $return["min_id"] = $comments->last()->id;
            $return["more"] = ($comments->count() >= 10) ? 'more' : 'none';
        }
        else
        {
            $return["html"] = '';
            $return["max_id"] = 0;
            $return["min_id"] = 0;
            $return["more"] = 'none';
        }

        return response_success($return);
    }








}