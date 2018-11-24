;(function () {
    'use strict';

    angular.module('question',[])
        .service('QuestionService',[
            '$http',
            '$state',
            function ($http,$state) {
                let me=this;
                me.new_question={};
                me.go_add_question=function () {
                    $state.go('question.add');
                };

                me.go_back=function () {
                    $state.go('home');
                };

                me.add_question=function () {
                    if (!me.new_question.title){
                        return;
                    }
                    $http.post('/question/add',me.new_question)
                        .then(function (r) {
                            if (r.data.status){
                                me.new_question={};
                                $state.go('home');

                            }
                        },function (e) {

                        })
                }
            }
        ])

        .controller('QuestionAddController',[
            '$scope',
            'QuestionService',
            function ($scope,QuestionService) {
                $scope.Question=QuestionService;
            }
        ])
})();