<div class="signup container" ng-controller="SignupController">
    <div class="root">
        <div class="signuppage-content">
            <div class="Card SignContainer-content">
                <div class="SignFlowHeader" style="padding-bottom:5px">
                    <img src="../pic/duanhubig.png" alt="" style="height: 68px;width: 120px;">
                    <div class="SignFlowHeader-slogen">注册<!-- -->段乎，发现更逗的段子</div>
                </div>
                <div class="Register" >
                    <div>
                        <form name="signup_form" ng-submit="User.signup()">
                            <div class="SignFlowInput">
                                <div class="SignFlowInput-User">
                                    <input name="username" class="in" type="text" spellcheck="false"
                                           ng-minlength="4" ng-maxlength="24" ng-model-options="{debounce:300}"
                                           required ng-model="User.signup_data.username" placeholder="用户名">
                                    <div class="input-error-set" ng-if="signup_form.username.$touched">
                                        <div ng-if="signup_form.username.$error.required" style="color: red;">请输入用户名</div>
                                        <div ng-if="signup_form.username.$error.minlength || signup_form.username.$error.maxlength" style="color: red;">用户名的长度应在4~24个字符之间</div>
                                        <div ng-if="User.signup_username_exists && !signup_form.username.$error.required" style="color: red;">用户名已存在</div>
                                    </div>
                                </div>
                                <div class="SignFlowInput-PSW">
                                    <input name="password" type="password" required ng-minlength="6" ng-maxlength="24" ng-model="User.signup_data.password" placeholder="密码">
                                    <div class="input-error-set" ng-if="signup_form.password.$touched">
                                        <div ng-if="signup_form.password.$error.required" style="color: red;">请输入密码</div>
                                    </div>
                                    <div class="input-error-set" ng-if="signup_form.password.$error.minlength || signup_form.password.$error.maxlength">
                                        <div style="color: red;">密码的长度应在6~24个字符之间</div>
                                    </div>
                                </div>
                            </div>
                            <div class="Register-Button">
                                <button class="button button-pill button-primary" ng-disabled="signup_form.password.$error.required || signup_form.username.$error.required">注册</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>