<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Hash;
use DB;

class User extends Model
{
    /*
     * 各人界面
     */
    public function personal(){
        if (!Request::get('id')){
            return ['status' => 0,'msg' => 'id is required'];
        }

        $get=['id','username','avatar_url','intro'];

        $user=$this->find(Request::get('id'),$get);
        $data=$user->toArray();
        $answer_count=answer_ins()->where('user_id',Request::get('id'))->count();
        $question_count=question_ins()->where('user_id',Request::get('id'))->count();
        //$answer_count=$user->answers()->count();
        //$question_count=$user->questions()->count();

        $data['question_count']=$question_count;
        $data['answer_count']=$answer_count;

        return ['status' => 1,'data' => $data];
    }
    /*
     * function sign_up
     * 注册方法
     */
    public function sign_up (){
        /*
         * 获取用户名和密码，实际为了安全考虑，应采用POST方法
         */
        $username=Request::get('username');
        $password=Request::get('password');

        /*
         * 检测用户名是否存在，应由Ajax完成前后端数据交换
         */
//        $user_exists=$this
//            ->where('username',$username)
//            ->exists();
//        if ($user_exists)
//            return ['status' => 0, 'msg' => '用户名已存在'];

        $hashed_password=Hash::make($password);//密码加密

        /*
         * 将用户名与密码等相关内容存入数据库
         */
        $user=$this;
        $user->username=$username;
        $user->password=$hashed_password;
        if ($user->save()){
            return ['status' => 1,'id' => $user->id];
        }else
            return ['status' => 0,'msg' => '注册失败'];
    }

    public function exist(){
        if ($this->where(Request::post())->count()!=0){
            return ['status' => 0,'data'=> [ 'count' => $this->where(Request::post())->count()]];
        }else{
            return ['status' => 1,'data'=> [ 'count' => $this->where(Request::post())->count()]];
        }
    }

    /*
     * function log_in
     * 登录方法
     */
    public function log_in (){
        /*
         * 获取登录见面POST回来的用户名与密码
         */
        $username=Request::post('username');
        $password=Request::post('password');

        /*
         * 检测用户名和密码是否正确
         */
        $user_exists=$this
            ->where('username',$username)
            ->exists();
        if ($user_exists){
            $hashed_password=DB::table('users')
                ->where('username',$username)
                ->value('password');
            if (Hash::check($password,$hashed_password)){
                $user_id=DB::table('users')->where('username',$username)->value('id');
                /*将用户信息写入session*/
                session()->put('username',$username);
                session()->put('user_id',$user_id);

                return ['status' => 1,
                    'id' => $user_id];
            }
            else return ['status' => 0,'msg' => '用户名或密码错误'];
        }
        else return ['status' => 0,'msg' => '用户名或密码错误'];
    }

    /*
     * 检测是否登录
     */
    public function is_login(){
        return is_login();
    }

    /*
     * function log_out()
     * 登出方法
     */
    public function log_out(){
        /*
         * 删除username和user_id
         */
        session()->forget('username');
        session()->forget('user_id');
        return ['status' => 1];
        //return redirect('/');
    }

    /*
     * 修改密码
     */
    public function change_password(){
        if (!user_ins()->is_login()){
            return ['status' => 0,'msg' => 'login required'];
        }

        if (!Request::post('old_password')||!Request::post('new_password')){
            return ['status' => 0,'msg' => 'old_password or new_password is required'];
        }

        $user=$this->find(session('user_id'));
        if (!Hash::check(Request::post('old_password'),$user->password)){
            return ['status' => 0,'msg' => 'invalid old_password'];
        }

        $user->password=Hash::make(Request::post('new_password'));

        return $user->save()?
            ['status' => 1]:
            ['status' => 0,'msg' => 'DB update failed'];
    }

    /*
     * 找回密码
     */
    public function reset_password(){
        /*
         * 防止重发
         */
        if($this->is_robot()){
            return ['status' => 0,'msg' => 'max frequency reached'];
        }

        if (!Request::post('phone')){
            return ['status' => 0,'msg' => 'phone is required'];
        }

        $user=$this->where(Request::post('phone'))->first();

        if (!$user){
            return ['status' => 0,'msg' => 'invalid phone number'];
        }

        $captcha=$this->generate_captcha();
        $user->phone_captcha=$captcha;

        if ($user->save()){
            //此时调用发送短信的API
            //send_sms();
            //验证短信验证码是否正确，如正确则修改对应用户的密码
            session()->set('last_action_time',time());
            return ['status' => 1];
        }else{
            return ['status' => 0,'msg' => 'DB insert failed'];
        }
    }

    public function validate_reset_password(){
        /*
         * 安全性考虑
         */
        if($this->is_robot()){
            return ['status' => 0,'msg' => 'max frequency reached'];
        }

        if (!Request::post('phone')||!Request::post('phone_captcha')){
            return ['status' => 0,'msg' => 'phone and phone_captcha are required'];
        }

        $user=$this->where([
            'phone' => Request::post('phone'),
            'phone_captcha' => Request::post('phone_captcha')
        ])->first();

        if (!$user){
            return ['status' => 0,'msg' => 'user not exists'];
        }

        $user->password=Hash::make(Request::post('new_password'));

        session()->set('last_action_time',time());
        return $user->save()?
            ['status' => 1]:
            ['status' => 0,'msg' => 'DB update failed'];
    }

    public function is_robot($time=10){
        /*
         * 通过操作的时间间隔来判断是否有机器人行为
         */
        $current_time=time();
        $last_action_time=session('last_action_time');

        return ($current_time-$last_action_time<=$time);
    }

    public function generate_captcha(){
        return rand(100000,999999);
    }

    public function questions(){
        return $this
            ->belongsToMany('App\Question')
            ->withPivot('vote')
            ->withTimestamps();
    }


    public function answers(){
        return $this
            ->belongsToMany('App\Answer')
            ->withPivot('vote')
            ->withTimestamps();
    }
}
