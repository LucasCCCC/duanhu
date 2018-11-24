<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;

class Question extends Model
{
    /*
     * function add()
     * 创建问题方法
     */
    public function add(){
        //登录检测
        if (!user_ins()->is_login()){
            return ['status' => 0,'msg' => 'log in required'];
        }

        //数据接收与保存
        $this->title=Request::post('title');
        $this->user_id=session('user_id');
        if (Request::post('desc')){
            $this->desc=Request::post('desc');
        }
        return $this->save()?
            ['status' => 1,'id' => $this->id]:
            ['status' => 0,'msg' => 'DB insert failed'];
    }

    /*
     * function edit()
     * 问题修改方法
     */
    public function edit(){
        //登录检测
        if (!user_ins()->is_login()){
            return ['status' => 0,'msg' => 'log in required'];
        }

        if(!Request::post('id')){
            return ['status' => 0,'msg' => 'id is required'];
        }
        $question=$this->find(Request::post('id'));
        if ($question->user_id != session('user_id')){
            return ['status' => 0,'msg' => 'permission denied'];
        }
        if (Request::post('title')){
            $question->title=Request::post('title');
        }
        if (Request::post('desc')){
            $question->title=Request::post('desc');
        }
        return $this->save()?
            ['status' => 1]:
            ['status' => 0,'msg' => 'DB update failed'];
    }

    /*
     * function rd()
     * 查看问题
     */
    public function rd(){
        //查看指定问题
        if (Request::get('id')){
            return ['status' => 1,'data' => $this->find(Request::get('id'))];
        }

        //$limit=15; //每页显示多少条数据
        //$skip=(Request::get('page')?Request::get('page')-1:0)*$limit; // 分页
        list($limit,$skip)=paginate(Request::get('page'),Request::get('limit'));

        //构建query并返回collection数据
        $cont=$this
            ->orderBy('created_at')
            ->limit($limit)
            ->skip($skip)
            ->get(['id','title','desc','user_id','created_at','updated_at'])
            ->keyBy('id');

        return ['status' => 1,'data' => $cont];
    }

    public function remove(){
        if (!user_ins()->is_login()){
            return ['status' => 0,'msg' => 'log in required'];
        }

        if(!Request::post('id')){
            return ['status' => 0,'msg' => 'ID is required'];
        }

        $question=$this->find(Request::post('id'));
        if (!queston){
            return ['status' => 0,'msg' => 'question is not exists'];
        }

        if (session('user_id')!=$question->user_id){
            return ['status' => 0,'msg' => 'permission denied'];
        }

        return $question->delete()?
            ['status' => 1]:
            ['status' => 0,'msg' => 'DB delete failed'];
    }

    /*
     * 创建question与users表的连接表，并注册vote，用于记录赞成与反对数目
     */
    public function user(){
        return $this
            ->belongsTo('App\User');
//            ->withPivot('vote')
//            ->withTimestamps();
    }
}
