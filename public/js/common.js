;(function () {
    'use strict';

    angular.module('common',[])
        .service('TimelineService',[
            '$http',
            'AnswerService',
            function ($http,AnswerService) {
                let me=this;
                me.data=[];
                me.current_page=1;
                /*
                获取首页数据
                 */
                me.get=function (conf) {
                    if(me.pending){
                        return;
                    }
                    me.pending=true;

                    conf=conf || {page:me.current_page};

                    $http.post('timeline',conf)
                        .then(function (r) {
                            if (r.data.status) {
                                if (r.data.data.length) {
                                    me.data=me.data.concat(r.data.data);
                                    /*
                                    统计每一条回答的票数
                                     */
                                    me.data=AnswerService.count_vote(me.data);
                                    me.current_page++;
                                }else {
                                    me.no_more_data=true;
                                }
                            }else {
                                console.error('network error');
                            }
                        },function () {
                            console.error('network error');
                        })
                        .finally(function () {
                            me.pending=false;
                        })
                };

                /*
                首页中，赞同与反对
                 */
                me.vote=function (conf) {
                    //调用核心投票功能
                    AnswerService.vote(conf)
                        .then(function (r) {
                            /*
                            如果赞同或反对成功，就更新AnswerService中的数据
                             */
                            if (r) {
                                AnswerService.update_data(conf.id);
                            }
                        })
                }
            }
        ])

        .controller('HomeController',[
            '$scope',
            'TimelineService',
            'AnswerService',
            function ($scope,TimelineService,AnswerService) {
                let $win;
                $scope.timeline=TimelineService;
                TimelineService.get();

                $win=$(window);
                $win.on('scroll',function () {
                    if($win.scrollTop() - ($(document).height-$win.height)> -30){
                        TimelineService.get();
                    }
                });


                /*
                监控回答数据的变化，如果有变化，就同时更新其他模块的Answer数据
                 */
                $scope.$watch(function () {
                    return AnswerService.data;
                },function (n) {
                    let timeline_data=TimelineService.data;
                    for (let k in n) {
                        /*
                        更新Timeline中的Answer数据
                         */
                        for (let i=0;i < timeline_data.length;i++){
                            if (k === timeline_data[i].id){
                                timeline_data[i] = n[k];
                            }
                        }
                    }
                    TimelineService.data=AnswerService.count_vote(TimelineService.data);
                },true);
            }
        ])
})();