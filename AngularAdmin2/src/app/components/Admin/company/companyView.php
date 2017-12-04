<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main relative">
	<h2 class="no-margin-top margin-bottom">My Company</h2>
	<div class="col-sm-12 no-padding-left no-padding-right small-margin-top">
        <div class="default margin-left margin-right" ng-hide="hidethis" ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error', fade: startFade }" ng-if="flash" ng-bind="flash.message"></div>    
	    <form name="form" ng-submit="vm.updateCompany()" role="form">
		    <div class="col-sm-6">
	        	<div class="well">
	            <fieldset class="fieldset">
	                <legend class="legend">Company Information</legend>
			        <div class="form-group" ng-class="{ 'has-error': form.legalName.$dirty && form.legalName.$error.required }">
			            <label for="legalName">Legal Name</label>
			            <input type="text" name="legalName" id="legalName" class="form-control" ng-model="vm.company.legalName" required />
			            <span ng-show="form.legalName.$dirty && form.legalName.$error.required" class="help-block">Legal Name is required</span>
			        </div>
			        <div class="form-group" ng-class="{ 'has-error': form.tradeName.$dirty && form.tradeName.$error.required }">
			            <label for="tradeName">Trade Name</label>
			            <input type="text" name="tradeName" id="Text1" class="form-control" ng-model="vm.company.tradeName" required />
			            <span ng-show="form.tradeName.$dirty && form.tradeName.$error.required" class="help-block">Trade Name is required</span>
			        </div>
			        <div class="form-group" ng-class="{ 'has-error': form.address1.$dirty && form.address1.$error.required }">
			            <label for="address1">Address 1</label>
			            <input type="text" name="address1" id="address1" class="form-control" ng-model="vm.company.address1" required />
			            <span ng-show="form.address1.$dirty && form.address1.$error.required" class="help-block">Address 1 is required</span>
			        </div>
			        <div class="form-group">
			            <label for="address2">Address 2</label>
			            <input type="text" name="address2" id="address2" class="form-control" ng-model="vm.company.address2" />
			        </div>
			        <div class="form-group" ng-class="{ 'has-error': form.city.$dirty && form.city.$error.required }">
			            <label for="city">City</label>
			            <input type="text" name="city" id="city" class="form-control" ng-model="vm.company.city" required />
			            <span ng-show="form.city.$dirty && form.city.$error.required" class="help-block">City is required</span>
			        </div>		        
					<div class="form-group" ng-class="{ 'has-error': form.state.$dirty && form.state.$error.required }">
					  <label for="state">State:</label>
					  <select class="form-control" id="state" ng-model="vm.company.state" required>
					    <option value="FL">Florida</option>
					    <option value="GA">Georgia</option>
					  </select>
					  <span ng-show="form.state.$dirty && form.state.$error.required" class="help-block">State is required</span>
					</div>
			        <div class="form-group" ng-class="{ 'has-error': form.zip.$dirty && form.zip.$error.required }">
			            <label for="zip">Zipcode</label>
			            <input type="text" name="zip" id="zip" class="form-control" ng-model="vm.company.zip" required />
			            <span ng-show="form.zip.$dirty && form.zip.$error.required" class="help-block">Zipcode is required</span>
			        </div>					
			    </fieldset>
			    </div>
	        </div>
	        <div class="col-sm-6">
	        	<div class="well">
		            <fieldset class="fieldset">
		                <legend class="legend">Company Contact</legend>
				        <div class="form-group" ng-class="{ 'has-error': form.contactName.$dirty && form.contactName.$error.required }">
				            <label for="address1">Name</label>
				            <input type="text" name="contactName" id="contactName" class="form-control" ng-model="vm.company.contactName" required />
				            <span ng-show="form.contactName.$dirty && form.contactName.$error.required" class="help-block">Contact name is required</span>
				        </div>
				        <div class="form-group" ng-class="{ 'has-error': form.contactPhone.$dirty && form.contactPhone.$error.required }">
				            <label for="contactPhone">Telephone</label>
				            <input type="text" name="contactPhone" id="contactPhone" class="form-control" ng-model="vm.company.contactPhone" required />
				            <span ng-show="form.contactPhone.$dirty && form.contactPhone.$error.required" class="help-block">Contact Phone is required</span>
				        </div>
				        <div class="form-group" ng-class="{ 'has-error': form.contactFax.$dirty && form.contactFax.$error.required }">
				            <label for="contactFax">Fax</label>
				            <input type="text" name="contactFax" id="contactFax" class="form-control" ng-model="vm.company.contactFax" />
				            <span ng-show="form.contactFax.$dirty && form.contactFax.$error.required" class="help-block">Fax is required</span>
				        </div>
				        <div class="form-group" ng-class="{ 'has-error': form.contactEmail.$dirty && form.contactEmail.$error.required }">
				            <label for="contactEmail">E-mail</label>
				            <input type="text" name="contactEmail" id="contactEmail" class="form-control" ng-model="vm.company.contactEmail" />
		   		            <span ng-show="form.contactEmail.$dirty && form.contactEmail.$error.required" class="help-block">E-mail is required</span>
				        </div>
				        <div class="form-group">
				            <label for="www">Website</label>
				            <input type="text" name="www" id="www" class="form-control" ng-model="vm.company.www" />
				        </div>				        
				    </fieldset>
	            </div>
	        	<div class="form-actions pull-right large-margin-bottom small-margin-right">
	                <button id="submit-btn" type="submit" ng-disabled="form.$invalid || vm.dataLoading" class="btn btn-primary">Submit</button>
	                <img ng-if="vm.dataLoading" src="data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==" />
	            </div> 
	        </div>
	    </form>
	</div>
</div>