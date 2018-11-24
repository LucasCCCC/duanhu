<!doctype html>
<html lang="zh" ng-app="duanhu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>段乎</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="../css/buttons.css">
    <link rel="stylesheet" href="../css/normalize-css/normalize.css">
    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/base.css">
    {{--<link rel="stylesheet" href="../css/zzsc.css">--}}
    {{--<link rel="stylesheet" href="https://cdn.staticfile.org/normalize/8.0.0/normalize.css">--}}
    {{--<script href="https://cdn.staticfile.org/jquery/3.3.1/jquery.js"></script>--}}
    {{--<script href="https://cdn.staticfile.org/angular.js/1.7.2/angular.js"></script>--}}
    <script src="../js/jquery/dist/jquery.js"></script>
    <script src="../js/angular/angular.js"></script>
    <script src="../js/angular-ui-router/release/angular-ui-router.js"></script>
    {{--<script href="https://cdn.staticfile.org/angular-ui-router/1.0.20/angular-ui-router.js"></script>--}}
    <script src="../js/base.js"></script>
    <script src="../js/user.js"></script>
    <script src="../js/question.js"></script>
    <script src="../js/answer.js"></script>
    <script src="../js/common.js"></script>
</head>
<body>
<div class="topbar">
    <div class="navbar clearfix">
        <a href="" ui-sref="home" >
            <img src="../pic/duanhu.png" alt="logo" class="logo">
        </a>
        <div class="fl">
            <div class="nav-item">
                <form action="" id="quick_ask" ng-controller="QuestionAddController" ng-submit="Question.go_add_question()">
                    <div class="search-input">
                        <input type="text" ng-model="Question.new_question.title">
                        <div class="quick-ask-button">
                            <button type="submit" class="button button-primary button-pill button-small" style="width: 100px">提问！</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="fr">
            @if(is_login())
                <div class="nav-item" >
                    <a href="" ui-sref="login">{{session('username')}}</a>
                </div>
                <div class="nav-item" >
                    <a href="{{url('/logOut')}}">登出</a>
                </div>
            @else
            <div class="nav-item" >
                <a href="" ui-sref="signup">注册</a>
            </div>
            <div class="nav-item">
                <a href="" ui-sref="login">登录</a>
            </div>
            @endif
            <div class="nav-item">item5</div>
        </div>
    </div>
</div>

<div class="page">
    <div ui-view></div>
</div>

</body>
<script src="../js/stickUp/stickUp.js"></script>
<script>
    //initiating jQuery
    jQuery(function($) {
        $(document).ready( function() {
            //enabling stickUp on the '.navbar-wrapper' class
            $('.topbar').stickUp();
        });
    });
</script>

</html>