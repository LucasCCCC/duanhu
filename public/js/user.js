;(function () {
    'use strict';

    angular.module('user',[])
        .service('UserService',[
            '$state',
            '$http',
            function ($state,$http) {
                let me = this;
                me.signup_data={};
                me.login_data={};
                me.login=function(){
                    $http.post('/logIn',me.login_data)
                        .then(function (r) {
                            if (r.data.status) {
                                //$state.go('/home');
                                location.href='/';
                            }else{
                                me.login_failed=true;
                            }
                        },function () {

                        })
                };
                me.signup=function () {
                    $http.post('/signUp',me.signup_data)
                        .then(function (r) {
                            if (r.data.status){
                                me.signup_data={};
                                $state.go('login');
                            }
                        },function (e) {
                            console.log('error',e);
                        })
                };

                me.username_exists=function () {
                    $http.post('/user/exist',
                        {username:me.signup_data.username})
                        .then(function (r) {
                            // if (!r.data.status && r.data.data.count){
                            //     me.signup_username_exists = true;
                            // }else {
                            //     me.signup_username_exists = false
                            // }
                            me.signup_username_exists = !!(!r.data.status && r.data.data.count);
                        },function (e) {

                        })
                };
            }])

        .controller('SignupController',[
            '$scope',
            'UserService',
            function ($scope,UserService) {
                $scope.User=UserService;

                $scope.$watch(function () {
                    return UserService.signup_data;
                },function (n,o) {
                    if (n.username!==o.username) {
                        UserService.username_exists();
                    }
                },true)
            }])

        .controller('LoginController',[
            '$scope',
            'UserService',
            function ($scope,UserService) {
                $scope.User=UserService;
            }
        ])
})();