<?php

namespace App;

use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\RedisQueue;
use Request;


class Comment extends Model
{
    /*
     * 添加评论
     */
    public function add(){
        //登录检测
        if (!user_ins()->is_login()){
            return ['status' => 0,'msg' => 'log in required'];
        }

        if (Request::post('content')){
            return ['status' => 0,'msg' => 'empty content'];
        }

        /*
         * 只能选择问题或回答中的一个进行评论
         */
        if (!Request::get('question_id')&&!Request::get('answer_id')||
            Request::get('question_id')&&Request::get('answer_id')){
            return ['status' => 0,'msg' => 'question_id or answer_id is required'];
        }

        /*
         * 评论问题或评论答案
         */
        if (Request::get('question_id')){
            $question=question_ins()->find(Request::get('question_id'));
            if (!$question){
                return ['status' => 0,'msg' => 'question not exists'];
            }
            $this->question_id=Request::get('question_id');
        }else{
            $answer=answer_ins()->find(Request::get('answer_id'));
            if (!$answer){
                return ['status' => 0,'msg' => 'answer not exists'];
            }
            $this->answer_id=Request::get('answer_id');
        }

        /*
         * 检查是否存在评论与是否在评论自己的评论
         */
        if (Request::post('reply_to')){
            $target=$this->find('reply_to');
            if (!$target) {
                return ['status' => 0,'msg' => 'target comment not exists'];
            }
            if($target->user_id==session('user_id')){
                return ['status' => 0,'msg' => 'cannot reply to yourself'];
            }
            $this->reply_to=Request::post('reply_to');
        }

        //评论的实现
        $this->content=Request::post('content');
        $this->user_id=session('user_id');
        return $this->save()?
            ['status' => 1,'id' => $this->id]:
            ['status' => 0,'msg' => 'DB insert failed'];
    }

    /*
     * 查看评论
     */
    public function read(){

        //检查是否有必要的参数
        if (!Request::get('question_id')||!Request::get('answer_id')){
            return ['status' => 0,'msg' => 'question or answer not exists'];
        }

        /*
         * 区分出问题评论与答案评论
         */
        if (Request::get('question_id')){
            $question=question_ins()->find(Request::get('question_id'));
            if (!$question){
                return ['status' => 0,'msg' => 'question not exists'];
            }
            $data=$this->where('question',Request::get('question_id'))->get();
        }else{
            $answer=answer_ins()->find(Request::get('answer_id'));
            if (!$answer){
                return ['status' => 0,'msg' => 'answer not exists'];
            }
            $data=$this->where('answer',Request::get('answer_id'))->get();
        }


        $data=$data->get()-keyBy('id');
        return ['status' => 1,'data' => $data->keyBy('id')];
    }

    /*
     * 删除评论
     */
    public function remove(){
        if (!user_ins()->is_login()){
            return ['status' => 0,'msg' => 'login required'];
        }

        if (!Request::get('id')){
            return ['status' => 0,'msg' => 'id required'];
        }

        $comment=$this->find(Request::get('id'));
        if (!$comment){
            return ['status' => 0,'msg' => 'comment not exists'];
        }
        if ($comment->user_id!=session('id')){
            return ['status' => 0,'msg' => 'permission denied'];
        }

        //先删除此评论的所有回复
        $this->where('reply_to',Request::get('id'))->delete();

        //然后删除评论
        return $comment->delete()?
            ['status' => 1]:
            ['status' => 0,'msg' => 'DB delete failed'];
    }
}
