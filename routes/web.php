<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
function paginate($page=1,$limit=15){
    $limit=$limit?:15;
    $skip=($page?$page-1:0)*$limit; // 分页
    return [$limit,$skip];
}

function user_ins(){
    return new App\User();
}

function question_ins(){
    return new App\Question();
}

function answer_ins(){
    return new App\Answer();
}

function comment_ins(){
    return new App\Comment();
}

function is_login(){
    return session('user_id')? : false;
}

Route::any('/', function () {
    return view('index');
});

Route::any('/user/change_password',function (){
    return user_ins()->change_password();
});

Route::any('/user/reset_password',function (){
    return user_ins()->reset_password();
});

Route::any('/user/validate_reset_password',function(){
    return user_ins()->validate_reset_password();
});

Route::any('/user/personal',function(){
    return user_ins()->personal();
});

Route::any('/user/exist',function (){
    return user_ins()->exist();
});

Route::any('/signUp',function(){
    return user_ins()->sign_up();
});

Route::any('/logIn',function(){
    return user_ins()->log_in();
});

Route::any('/logOut',function(){
   return user_ins()->log_out();
});

Route::any('/question/add',function(){
   return question_ins()->add();
});

Route::any('/question/edit',function (){
    return question_ins()->edit();
});

Route::any('/question/rd',function(){
    return question_ins()->rd();
});

Route::any('/question/remove',function(){
    return question_ins()->remove();
});

Route::any('/answer',function(){
    return answer_ins()->add();
});

Route::any('/answer/edit',function (){
    return answer_ins()->edit();
});

Route::any('/answer/read',function (){
    return answer_ins()->read();
});

Route::any('/answer/vote',function (){
    return answer_ins()->vote();
});

Route::any('/comment',function(){
    return comment_ins()->add();
});

Route::any('/comment/read',function (){
    return comment_ins()->read();
});

Route::any('/comment/remove',function (){
    return comment_ins()->remove();
});

Route::any('/timeline','CommonController@timeline');

Route::get('tpl/page/home',function (){
    return view('page.home');
});

Route::get('tpl/page/login',function (){
    return view('page.login');
});

Route::get('tpl/page/signup',function (){
    return view('page.signup');
});

Route::get('tpl/page/question_add',function (){
    return view('page.question_add');
});




