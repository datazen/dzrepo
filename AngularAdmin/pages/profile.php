<?php
if (file_exists('../inc/header.inc.php')) include '../inc/header.inc.php';
if (file_exists('../inc/sidebar.inc.php')) include '../inc/sidebar.inc.php';
?> 

<div class="profile col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h2 class="no-margin-top margin-bottom">My Profile</h2>
    <div class="col-sm-12 no-padding-left no-padding-right">
        <div class="default" ng-hide="hidethis" ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error', fade: startFade }" ng-if="flash" ng-bind="flash.message"></div>

        <div class="col-sm-4 no-padding-left">
            <div class="well">
                 <label for="avatar">Avatar <small class="margin-left">&nbsp;&nbsp;(128x128)</small></label>
                 <div class="avatar margin-bottom">
                     <img ng-src="img/{{vm.user.avatar}}">
                 </div>
                <form class="form-inline" name="aform" role="form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="form-label">
                          <input type="file" name="myFile" id="myFile" file-model="myFile" />
                          <img ng-if="vm.dataLoading2" src="data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==" />
                        </label>

                    </div>  
                    <div class="form-actions align-right hidden">
                        <button id="submitButton" type="submit" ng-click="vm.updateAvatar()" ng-disabled="form.$invalid || vm.dataLoading" class="btn btn-primary">Update</button>
                    </div>  
                </form>
            </div>
        </div>   

        <div class="col-sm-8 no-padding-right">
            <div class="well">
                <form name="form" ng-submit="vm.updateProfile()" role="form">
                    <div class="form-group" ng-class="{ 'has-error': form.firstName.$dirty && form.firstName.$error.required }">
                        <label for="username">First name</label>
                        <input type="text" name="firstName" id="firstName" class="form-control" ng-model="vm.user.firstName" required />
                        <span ng-show="form.firstName.$dirty && form.firstName.$error.required" class="help-block">First name is required</span>
                    </div>
                    <div class="form-group" ng-class="{ 'has-error': form.lastName.$dirty && form.lastName.$error.required }">
                        <label for="username">Last name</label>
                        <input type="text" name="lastName" id="Text1" class="form-control" ng-model="vm.user.lastName" required />
                        <span ng-show="form.lastName.$dirty && form.lastName.$error.required" class="help-block">Last name is required</span>
                    </div>
                    <div class="form-group" ng-class="{ 'has-error': form.username.$dirty && form.username.$error.required }">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" ng-model="vm.user.username" required />
                        <span ng-show="form.username.$dirty && form.username.$error.required" class="help-block">Username is required</span>
                    </div>

                    <div class="form-group" ng-class="{ 'has-error' : form.password.$dirty && form.password.$invalid }">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" ng-model="vm.user.password" password-verify="{{vm.user.confirmPassword}}" required />
                        <div class="help-block" ng-messages="form.password.$error" ng-if="form.password.$dirty">
                            <p ng-message="required">This field is required</p>
                            <p ng-message="minlength">This field is too short</p>
                            <p ng-message="maxlength">This field is too long</p>
                            <p ng-message="required">This field is required</p>
                            <p ng-message="passwordVerify">No match!</p>
                        </div>
                    </div>
                    <div class="form-group" ng-class="{ 'has-error' : form.confirmPassword.$dirty && form.confirmPassword.$invalid }">
                        <label for="confirmPassword">Confirm Password</label>
                        <input class="form-control" id="confirmPassword" ng-model="vm.user.confirmPassword" name="confirmPassword" type="password" password-verify="{{vm.user.password}}" required />
                        <div class="help-block" ng-messages="form.confirmPassword.$error" ng-if="form.confirmPassword.$dirty">
                            <p ng-message="required">This field is required</p>
                            <p ng-message="minlength">This field is too short</p>
                            <p ng-message="maxlength">This field is too long</p>
                            <p ng-message="required">This field is required</p>
                            <p ng-message="passwordVerify">No match!</p>
                        </div>
                    </div>

                    <div class="form-actions align-right">
                        <a ng-href="#!/dashboard" class="btn btn-link">Cancel</a>
                        <button type="submit" ng-disabled="form.$invalid || vm.dataLoading" class="btn btn-primary">Update</button>
                        <img ng-if="vm.dataLoading" src="data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>

$( "#myFile" ).change(function() {
    setTimeout(function(){
      $('#submitButton').click();
    }, 500);
});
</script>