<?php
namespace App\Repositories\Home;

use App\Models\Topic;
use App\Models\Communication;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;
use Symfony\Component\Console\Helper\Table;

class TopicRepository {

    private $model;
    public function __construct()
    {
//        $this->model = new People;
    }

    public function index()
    {
        return view('home.index');
    }

    // 返回列表数据
    public function get_list_datatable($post_data)
    {
        $user = Auth::user();
        $query = Topic::select("*")->with(['user'])->where('user_id', $user->id);
        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");
        if(!empty($post_data['major'])) $query->where('major', 'like', "%{$post_data['major']}%");
        if(!empty($post_data['nation'])) $query->where('nation', 'like', "%{$post_data['nation']}%");
        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("updated_at", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
        return datatable_response($list, $draw, $total);
    }

    // 返回添加视图
    public function view_create()
    {
        return view('home.topic.edit');
    }
    // 返回编辑视图
    public function view_edit()
    {
        $id = request("id",0);
        $decode_id = decode($id);
        if(!$decode_id && intval($id) !== 0) return view('home.404');

        if($decode_id == 0)
        {
            return view('home.topic.edit')->with(['operate'=>'create', 'encode_id'=>$id]);
        }
        else
        {
            $data = Topic::find($decode_id);
            if($data)
            {
                unset($data->id);
                return view('home.topic.edit')->with(['operate'=>'edit', 'encode_id'=>$id, 'data'=>$data]);
            }
            else return response("该课程不存在！", 404);
        }
    }

    // 保存数据
    public function save($post_data)
    {
        $messages = [
            'id.required' => '参数有误',
            'title.required' => '请输入课程标题',
        ];
        $v = Validator::make($post_data, [
            'id' => 'required',
            'title' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $user = Auth::user();

        $id = decode($post_data["id"]);
        $operate = $post_data["operate"];
        if(intval($id) !== 0 && !$id) return response_error();

        DB::beginTransaction();
        try
        {
            if($operate == 'create') // $id==0，添加一个新的课程
            {
                $topic = new Topic;
                $post_data["user_id"] = $user->id;
            }
            elseif('edit') // 编辑
            {
                $topic = Topic::find($id);
                if(!$topic) return response_error([],"该课程不存在，刷新页面重试");
                if($topic->user_id != $user->id) return response_error([],"你没有操作权限");
            }
            else throw new Exception("operate--error");

            if(!empty($post_data['is_anonymous']) && $post_data['is_anonymous'] == 'on') $post_data['is_anonymous'] = 1;
            $bool = $topic->fill($post_data)->save();
            if($bool)
            {
                $encode_id = encode($topic->id);

                if(!empty($post_data["cover"]))
                {
                    $upload = new CommonRepository();
                    $result = $upload->upload($post_data["cover"], 'unique-cover-topics' , 'cover_topic_'.$encode_id);
                    if($result["status"])
                    {
                        $topic->cover_pic = $result["data"];
                        $topic->save();
                    }
                    else throw new Exception("upload--cover--fail");
                }
            }
            else throw new Exception("insert--people--fail");


            DB::commit();
            return response_success(['id'=>$encode_id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
//            exit($e->getMessage());
//            $msg = $e->getMessage();
            $msg = '操作失败，请重试！';
            return response_fail([], $msg);
        }
    }

    // 删除
    public function delete($post_data)
    {
        $user = Auth::user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该课程不存在，刷新页面试试");

        $topic = Topic::find($id);
        if($topic->user_id != $user->id) return response_error([],"你没有操作权限");

        DB::beginTransaction();
        try
        {
            $bool = $topic->delete();
            if(!$bool) throw new Exception("delete--topic--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'删除失败，请重试');
        }

    }

    // 启用
    public function enable($post_data)
    {
        $user = Auth::user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该作者不存在，刷新页面试试");

        $topic = Topic::find($id);
        if($topic->user_id != $user->id) return response_error([],"你没有操作权限");
        $update["active"] = 1;
        DB::beginTransaction();
        try
        {
            $bool = $topic->fill($update)->save();
            if(!$bool) throw new Exception("update--topic--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'启用失败，请重试');
        }
    }

    // 禁用
    public function disable($post_data)
    {
        $user = Auth::user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该文章不存在，刷新页面试试");

        $topic = Topic::find($id);
        if($topic->user_id != $user->id) return response_error([],"你没有操作权限");
        $update["active"] = 9;
        DB::beginTransaction();
        try
        {
            $bool = $topic->fill($update)->save();
            if(!$bool) throw new Exception("update--topic--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'禁用失败，请重试');
        }
    }






    //
    public function select2_menus($post_data)
    {
        $topic_encode = $post_data['topic_id'];
        $topic_decode = decode($topic_encode);
        if(!$topic_decode) return view('home.404')->with(['error'=>'参数有误']);

        if(empty($post_data['keyword']))
        {
            $list =Content::select(['id','title as text'])->where('topic_id', $topic_decode)->get()->toArray();
        }
        else
        {
            $keyword = "%{$post_data['keyword']}%";
            $list =Content::select(['id','title as text'])->where('topic_id', $topic_decode)->where('name','like',"%$keyword%")->get()->toArray();
        }
        return $list;
    }




    // 层叠排列
    function get_tree($a,$pid=0)
    {
        $tree = array();
        //每次都声明一个新数组用来放子元素
        foreach($a as $v)
        {
            if($v->p_id == $pid)
            {
                //匹配子记录
                $v->children = $this->get_tree($a, $v->id); //递归获取子记录

                if($v->children == null)
                {
                    unset($v->children); //如果子元素为空则unset()进行删除，说明已经到该分支的最后一个元素了（可选）
                }
                $tree[] = $v; //将记录存入新数组
            }
        }
        return $tree; //返回新数组
    }
    // 层叠排列
    function get_tree_array($a,$pid=0)
    {
        $tree = array();
        //每次都声明一个新数组用来放子元素
        foreach($a as $v)
        {
            if($v['p_id'] == $pid)
            {
                //匹配子记录
                $v['children'] = $this->get_tree_array($a, $v['id']); //递归获取子记录

                if($v['children'] == null)
                {
                    unset($v['children']); //如果子元素为空则unset()进行删除，说明已经到该分支的最后一个元素了（可选）
                }
                $tree[] = $v; //将记录存入新数组
            }
        }
        return $tree; //返回新数组
    }


    // 顺序排列
    function get_recursion($result, $parent_id=0, $level=0)
    {
        /*记录排序后的类别数组*/
        static $list = array();

        foreach ($result as $k => $v)
        {
            if($v->p_id == $parent_id)
            {
                $v->level = $level;

                foreach($list as $key=>$val)
                {
                    if($val->id == $parent_id) $list[$key]->has_child = 1;
                }

                /*将该类别的数据放入list中*/
                $list[] = $v;

                $this->get_recursion($result, $v->id, $level+1);
            }
        }

        return $list;
    }
    // 顺序排列
    function get_recursion_array($result, $parent_id=0, $level=0)
    {
        /*记录排序后的类别数组*/
        static $list = array();

        foreach ($result as $k => $v)
        {
            if($v['p_id'] == $parent_id)
            {
                $v['level'] = $level;

                foreach($list as $key=>$val)
                {
                    if($val['id'] == $parent_id) $list[$key]['has_child'] = 1;
                }

                /*将该类别的数据放入list中*/
                $list[] = $v;

                $this->get_recursion_array($result, $v['id'], $level+1);
            }
        }

        return $list;
    }




}