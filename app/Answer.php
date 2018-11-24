<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;

class Answer extends Model
{
    /*
     * 添加回答
     */
    public function add(){

        //登录检测
        if (!user_ins()->is_login()){
            return ['status' => 0,'msg' => 'log in required'];
        }

        //参数正常与否检测
        if (!Request::get('question_id')||!Request::post('content')){
            return ['status' => 0,'msg' => 'question id or content required'];
        }
        $queston=question_ins()->find(Request::get('question_id'));
        if (!$queston){
            return ['status' => 0,'msg' => 'question not exists'];
        }

        //重复回答检测
        $answered=$this
            ->where(['question_id' => Request::get('question_id'),'user_id' => session('user_id')])
            ->count();
        if ($answered){
            return ['status' => 0,'msg' => 'duplicate answers'];
        }

        //保存数据
        $this->content=Request::post('content');
        $this->question=Request::post('queston_id');
        $this->user_id=session('user_id');

        return $this->save()?
            ['status' => 1,'id' => $this->id]:
            ['status' => 0,'msg' => 'DB insert failed'];
    }

    /*
     * 修改回答
     */
    public function edit(){
        if (!user_ins()->is_login()){
            return ['status' => 0,'msg' => 'log in required'];
        }

        if (!Request::get('id')){
            return ['status' => 0,'msg' => 'id is required'];
        }

        $answer=$this->find(Request::get('id'));
        if ($answer->user_id!=session('user_id')){
            return ['status' => 0,'msg' => 'permission denied'];
        }

        if (!Request::post('content')){
            return ['status' => 0,'msg' => 'content is not exists'];
        }

        $answer->content=Request::post('content');
        return $this->save()?
            ['status' => 1]:
            ['status' => 0,'msg' => 'DB update failed'];
    }

    /*
     * 查看回答
     */
    public function read(){

        //检查问题或回答是否存在
        if (!Request::get('id')&&!Request::get('question_id')){
            return ['status' => 0,'msg' => 'id or question_id is required'];
        }

        //查看单个回答
        if (Request::get('id')){
            $answer=$this
                ->with('user')
                ->with('users')
                ->find(Request::get('id'));
            if (!$answer){
                return ['status' => 0,'msg' => 'answer not exists'];
            }
            return ['status' => 1,'data' => $answer];
        }

        //查看一个问题的所有回答
        if (!question_ins()->find(Request::get('question_id'))){
            ['status' => 0,'msg' => 'question not exists'];
        }
        $answers=$this
            ->where('question_id',Request::get('question_id'))
            ->get()
            ->keyBy('id');

        return ['status' => 1,'data' => $answers];
    }

    /*
     * 赞成与反对
     */
    public function vote(){
        if (!user_ins()->is_login()){
            return ['status' => 0,'msg' => 'log in required'];
        }

        if (!Request::get('id')||!Request::get('vote')){
            return ['status' => 0,'msg' => 'id and vote required'];
        }

        $answer=$this->find(Request::get('id'));
        if (!$answer){
            return ['status' => 0,'msg' => 'answer not exists'];
        }

        //1为赞成，2为反对,3为清空
        $vote=Request::get('vote');
        if ($vote!=1 && $vote!=2 && $vote!=3){
            return ['status' => 0,'msg' => 'invalid vote'];
        }

        /*
         * 检查相同用户是否赞成或反对相同问题,如果已经赞成或反对，则清空结果
         */
        $answer
            ->users()
            ->newPivotStatement()
            ->where('user_id',session('user_id'))
            ->where('answer_id',Request::get('id'))
            ->delete();

        if ($vote === 3){
            return ['status' => 1];
        }

        //在连接表中增加数据
        $answer->users()->attach(session('user_id'),['vote' => $vote]);

        return ['status' => 1];
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    /*
     * 创建answers与users表的连接表，并注册vote，用于记录赞成与反对数目
     */
    public function users(){
        return $this
            ->belongsToMany('App\User')
            ->withPivot('vote')
            ->withTimestamps();
    }
}
