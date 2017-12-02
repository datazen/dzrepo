    <div class="col-sm-6 no-padding-left">
    	<div class="well">
		    <div class="form-group" ng-class="{ 'has-error': form.firstName.$dirty && form.firstName.$error.required }">
		        <label for="username">First name</label>
		        <input type="text" name="firstName" id="firstName" class="form-control" ng-model="vm.userRecord.firstName" required />
		        <span ng-show="form.firstName.$dirty && form.firstName.$error.required" class="help-block">First name is required</span>
		    </div>
		    <div class="form-group" ng-class="{ 'has-error': form.lastName.$dirty && form.lastName.$error.required }">
		        <label for="username">Last name</label>
		        <input type="text" name="lastName" id="Text1" class="form-control" ng-model="vm.userRecord.lastName" required />
		        <span ng-show="form.lastName.$dirty && form.lastName.$error.required" class="help-block">Last name is required</span>
		    </div>
			<div class="form-group" ng-class="{ 'has-error': form.accessLevel.$dirty && form.accessLevel.$error.required }">
				<label class="control-label" for="accessLevel">Access Level</label>
                <select name="accessLevel" id="accessLevel" class="form-control" ng-model="vm.userRecord.accessLevel" ng-disabled="(vm.userRecord.username == 'admin')">
                    <option ng-repeat="level in vm.levels" value="{{level.level}}">{{level.level}} - {{level.name}}</option>
                </select>
				<span ng-show="form.accessLevel.$dirty && form.accessLevel.$error.required" class="help-block">Access level is required</span>
			</div>		    
		</div>
	</div>
	<div class="col-sm-6 no-padding-right">
		<div class="well">
		    <div class="form-group" ng-class="{ 'has-error': form.username.$dirty && form.username.$error.required }">
		        <label for="username">Username</label>
		        <input type="text" name="username" id="username" class="form-control" ng-model="vm.userRecord.username" ng-readonly="(vm.isEditUser && vm.userRecord.username)" required />
		        <span ng-show="form.username.$dirty && form.username.$error.required" class="help-block">Username is required</span>
		    </div>

		    <div class="form-group" ng-class="{ 'has-error' : form.password.$dirty && form.password.$invalid }">
		        <label for="password">Password</label>
		        <input type="password" class="form-control" id="password" name="password" ng-model="vm.userRecord.password" password-verify="{{vm.userRecord.confirmPassword}}" ng-required="!vm.isEditUser" />
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
		        <input class="form-control" id="confirmPassword" ng-model="vm.userRecord.confirmPassword" name="confirmPassword" type="password" password-verify="{{vm.userRecord.password}}" ng-required="!vm.isEditUser" />
		        <div class="help-block" ng-messages="form.confirmPassword.$error" ng-if="form.confirmPassword.$dirty">
		            <p ng-message="required">This field is required</p>
		            <p ng-message="minlength">This field is too short</p>
		            <p ng-message="maxlength">This field is too long</p>
		            <p ng-message="required">This field is required</p>
		            <p ng-message="passwordVerify">No match!</p>
		        </div>
		    </div>
		</div>

		    <div class="form-actions align-right">
		        <a ng-href="#!/Admin/users" class="btn btn-link">Cancel</a>
		        <button type="submit" ng-disabled="form.$invalid || vm.dataLoading" class="btn btn-primary">Update</button>
		        <img ng-if="vm.dataLoading" src="data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==" />
		    </div>
    </div>