<?php
namespace App\Repositories\Home;

use App\User;
use App\Administrator;
use App\Models\Verification;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;

class AuthRepository {

    private $model;
    public function __construct()
    {
    }

    // 注册用户
    public function register($post_data)
    {
        $messages = [
            'name.required' => '请填写用户名',
            'anonymous_name.required' => '请填写匿名时花名',
            'anonymous_name.unique' => '花名已存在，请更换花名',
            'captcha.required' => '请输入验证码',
            'captcha.captcha' => '验证码有误',
            'email.required' => '请填写邮箱',
            'email.unique' => '邮箱已存在，请更换邮箱',
        ];
        $v = Validator::make($post_data, [
            'captcha' => 'required|captcha',
            'name' => 'required',
            'anonymous_name' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'password_confirm' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $name = $post_data['name'];
        $anonymous_name = $post_data['anonymous_name'];
        $email = $post_data['email'];
        $password = $post_data['password'];
        $password_confirm = $post_data['password_confirm'];
        if($password == $password_confirm)
        {
            DB::beginTransaction();
            try
            {
                // 注册超级管理员
                $user = new User;
                $user_create['name'] = $name;
                $user_create['anonymous_name'] = $anonymous_name;
                $user_create['email'] = $email;
                $user_create['password'] = password_encode($password);
                $bool = $user->fill($user_create)->save();
                if($bool)
                {
                    $string = '&user_id='.$user->id.'&time='.time();
                    $code = hash("sha512", $string);

                    $verification_create['type'] = 1;
                    $verification_create['user_id'] = $user->id;
                    $verification_create['email'] = $email;
                    $verification_create['code'] = $code;

                    $verification = new Verification;
                    $bool4 = $verification->fill($verification_create)->save();
                    if($bool4)
                    {
                        $post_data['host'] = config('common.host.online.root');
                        $post_data['sort'] = 'email_activation';
                        $post_data['type'] = 1;
                        $post_data['user_id'] = encode($user->id);
                        $post_data['code'] = $code;
                        $post_data['target'] = $email;

                        $url = config('common.MailService').'/topic/email/activation';
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 7);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                        $response = curl_exec($ch);
                        curl_close($ch);
                        if(empty($response)) throw new Exception('curl get request failed');
                        else
                        {
                            $response = json_decode($response, true);
                            if(!$response['success']) throw new Exception("send-email-failed");
                        }
                    }
                }
                else throw new Exception("insert-user-failed");

                DB::commit();
                return response_success([],'注册成功,请前往邮箱激活账户');
            }
            catch (Exception $e)
            {
                DB::rollback();
//                exit($e->getMessage());
//                $msg = $e->getMessage();
                $msg = '注册失败，请重试！';
                return response_fail([],$msg);
            }
        }
        else return response_error([],'密码不一致！');
    }

    // 激活邮箱
    public function activation($post_data)
    {
        $user_id = decode($post_data['user']);
        $where['user_id'] = $user_id;
        $where['type'] = $post_data['type'];
        $where['code'] = $post_data['code'];
        $verification = Verification::where($where)->first();
        if($verification)
        {
            if($verification->active == 0)
            {
                $user = User::where('id',$user_id)->first();
                if($user)
                {
                    $user->active = 1;
                    $bool1 = $user->save();
                    if($bool1)
                    {
                        $verification->active = 1;
                        $bool2 = $verification->save();
                        header("Refresh:4;url=/home");
                        if($bool2) echo('验证成功，5秒后跳转后台页面！');
                        else echo('验证成功2，5秒后跳转后台页面！');
                    }
                    else dd('验证失败');
                }
            }
            else
            {
                header("Refresh:3;url=/home");
                echo('已经验证过了，3秒后跳转后台页面！');
            }
        }
        else dd('参数有误');
    }


}