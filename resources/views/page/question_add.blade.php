<div class="new-question root" ng-controller="QuestionAddController">
    <div class="question-add container">
        <div class="Card question-add-content">
            <form name="question_add_form" ng-submit="Question.add_question()">
                <div class="question-title">
                    <input type="text" name="title" class="input-group question-title-input" placeholder="写下你的问题"
                           ng-model="Question.new_question.title" required ng-minlength="5" ng-maxlength="255">
                </div>
                <div class="question-desc">
                    <label for="desc">问题说明（可选）</label>
                    <textarea type="text" name="desc" class="input-group question-desc-input" id="desc" placeholder="问题背景，条件等详细信息"
                              ng-model="Question.new_question.desc"></textarea>
                </div>
                <div class="question-add-button">
                    <button class="button button-glow button-border button-rounded button-primary button-back"
                            ng-click="Question.go_back()">取消</button>
                    <button class="button button-glow button-rounded button-royal button-sub" ng-disabled="question_add_form.$invalid">发布</button>
                </div>
            </form>
        </div>
    </div>
</div>