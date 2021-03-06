;(function () {
    'use strict';

    angular.module('duanhu',[
        'ui.router',
        'user',
        'question',
        'answer',
        'common',
    ])
        .config(function ($interpolateProvider,$stateProvider,$urlRouterProvider) {
            $interpolateProvider.startSymbol('[:');
            $interpolateProvider.endSymbol(':]');

            $urlRouterProvider.otherwise('/home');

            $stateProvider
                .state('home',{
                url:'/home',
                templateUrl:'/tpl/page/home'
            })
                .state('login',{
                    url:'/login',
                    templateUrl:'/tpl/page/login'
                })
                .state('signup',{
                    url:'/signup',
                    templateUrl:'/tpl/page/signup'
                })
                .state('question',{
                    abstract:true,
                    url:'/question',
                    template:'<div ui-view></div>'
                })
                .state('question.add',{
                    url:'/add',
                    templateUrl:'/tpl/page/question_add'
                })
        })
})();