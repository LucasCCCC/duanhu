<div class="home container" ng-controller="HomeController">
    <div class="hr">
        <h1>最近动态</h1>
    </div>
    <div class="time-line-content">
        <div class="time-line-item clearfix" ng-repeat="item in timeline.data">
            <div class="item-content clearfix">
                <div class="item-activity" ng-if="item.question_id">[:item.user.username:] 添加了回答</div>
                <div class="item-activity" ng-if="!item.question_id">[:item.user.username:] 想知道</div>
                <div class="item-title">[:item.title:]</div>
                <div class="item-auth">[:item.user.username:]</div>
                <div class="item-main" ng-if="item.question_id">[:item.content:]</div>
                <div class="item-main" ng-if="!item.question_id">[:item.desc:]</div>
                <div class="item-tool-bar">
                    <span>
                        <div class="vote" ng-if="item.question_id">
                            <button class="button-up" ng-click="timeline.vote({id:item.id,vote:1})">
                                <span  style="display: inline-flex; align-items: center">
                                    <i class="fa fa-caret-up" style="width: 10px; height: 10px; margin-right: 5px"></i>
                                </span>
                                赞同 [:item.up_vote_count:]
                            </button>
                            <button class="button-down" ng-click="timeline.vote({id:item.id,vote:2})">
                                <span style="display: inline-flex; align-items: center">
                                    <i class="fa fa-caret-down"></i>
                                </span>
                            </button>
                        </div>
                    </span>
                    <button class="button-comment">
                        <span><i class="fa fa-comment"></i></span>
                        10 条评论
                    </button>
                </div>
                <div class="comment-block" name="comment-block">
                    <div class="comment-block-item">
                        <div class="comment-content">
                            <div class="comment-hr">
                                <div class="comment-content-user">宁姚</div>
                                <div class="comment-content-main">呵呵</div>
                            </div>
                            <div class="comment-hr">
                                <div class="comment-content-user">茅晓东</div>
                                <div class="comment-content-main">还不是靠我</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <span class="hr"></span>
        </div>
        <div class="tac" ng-if="timeline.no_more_data">
            {{--<div class="typing-loader"></div>--}}
            加载中...
        </div>
        <div class="tac" ng-if="timeline.no_more_data">没有更多数据啦♪(^∇^*)</div>
    </div>
</div>