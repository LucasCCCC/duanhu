<?php

namespace App\Http\Controllers;

use Request;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class CommonController extends Controller
{
    //时间线
    public function timeline(){
        list($limit,$skip)=paginate(Request::get('page'),Request::get('limit'));

        //获取问题数据
        $questions=question_ins()
            ->with('user')
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at','desc')
            ->get();

        //获取答案数据
        $answers=answer_ins()
            ->with('users')
            ->with('user')
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at','desc')
            ->get();


        //合并数据并按时间排序
        $data=$questions->concat($answers);

        $data=$data->sortBy(function ($item){
            return $item->created_at;
        });

        $data=$data->values()->all();

        return ['status' => 1,'data' =>$data];
    }
}
