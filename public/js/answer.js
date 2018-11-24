;(function () {
    'use strict';

    angular.module('answer',[])
        .service('AnswerService',[
            '$http',
            function ($http) {
                let me=this;
                me.data={};
                /**
                 *
                 * @param answers array 用于统计赞同与反对数量的数据，可以是问题，也可以是回答，
                 *                      如果是问题将会跳过统计
                 * @returns {answer}
                 */
                me.count_vote=function (answers) {
                    /*迭代所有数据*/
                    for (let i=0;i<answers.length;i++){
                        /*封装单个数据*/
                        let item=answers[i];
                        /*如果不是回答，或者没有users数据，则说明没有票数*/
                        if (!item['question_id'] || !item['users']) {
                            continue;
                        }
                        item.up_vote_count=0;
                        item.down_vote_count=0;
                        /*users是所有投票的用户信息，封装users数据，以便从中得到赞同与反对的相关数据*/
                        let votes=item['users'];

                        /*迭代所有封装好的users数据，统计赞同与反对的数量，如果是1，赞同+1，如果是2，反对+1*/
                        for (let j=0;j<votes.length;j++){
                            let v=votes[j];
                            if (v['pivot'].vote === 1) {
                                item.up_vote_count++;
                            }
                            if (v['pivot'].vote === 2){
                                item.down_vote_count++;
                            }
                        }
                    }
                    return answers;
                };

                me.vote=function (conf) {
                    if (!conf.id || !conf.vote){
                        console.log('id and vote are required');
                        return;
                    }

                    return $http.post('/answer/vote',conf)
                        .then(function (r) {
                            return !!r.data.status;
                        },function () {
                            return false;
                        })
                };

                me.update_data=function (id) {
                    return $http.post('/answer/read',{id:id})
                        .then(function (r) {
                            me.data[id]=r.data.data;
                        })
                }
            }
        ])
})();