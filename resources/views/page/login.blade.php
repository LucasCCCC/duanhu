<div class="login container" ng-controller="LoginController">
    <div class="root">
        <div class="loginpage-content">
            <div class="Card LoginContainer-content">
                <div class="LoginFlowHeader" style="padding-bottom:5px">
                    <img src="../pic/duanhubig.png" alt="" style="height: 68px;width: 120px;">
                    <div class="LoginFlowHeader-slogen">登录<!-- -->段乎，发现更逗的段子</div>
                </div>
                <div class="Login" >
                    <div>
                        <form name="login_form" ng-submit="User.login()">
                            <div class="LoginFlowInput">
                                <div class="LoginFlowInput-User">
                                    <input name="username" class="in" type="text" spellcheck="false"
                                           ng-minlength="4" ng-maxlength="24" ng-model-options="{debounce:300}"
                                           required ng-model="User.login_data.username" placeholder="用户名">
                                    <div class="input-error-set" ng-if="login_form.username.$touched">
                                        <div ng-if="login_form.username.$error.required" style="color: red;">请输入用户名</div>
                                        <div ng-if="login_form.username.$error.minlength || login_form.username.$error.maxlength" style="color: red;">用户名的长度应在4~24个字符之间</div>
                                    </div>
                                </div>
                                <div class="LoginFlowInput-PSW">
                                    <input name="password" type="password" required ng-minlength="6" ng-maxlength="24"
                                           ng-model="User.login_data.password"  placeholder="密码">
                                    <div class="input-error-set" ng-if="login_form.password.$touched">
                                        <div ng-if="login_form.password.$error.required" style="color: red;">请输入密码</div>
                                        <div  ng-if="login_form.password.$error.minlength || login_form.password.$error.maxlength" style="color: red;">密码的长度应在6~24个字符之间</div>
                                        <div ng-show="User.login_failed" style="color: red;">用户名或密码错误</div>
                                    </div>
                                </div>
                            </div>
                            <div class="Login-Button">
                                <button class="button button-pill button-primary"
                                        ng-disabled="login_form.username.$error.required || login_form.password.$error.required">登录</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>