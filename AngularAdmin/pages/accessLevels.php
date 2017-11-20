<?php
if (file_exists('../inc/header.inc.php')) include '../inc/header.inc.php';
if (file_exists('../inc/sidebar.inc.php')) include '../inc/sidebar.inc.php';
?> 
<style>
.modal-backdrop {
   opacity: 0.8 !important;
}
.modal {
   top: 5% !important;
   opacity: 1 !important;
}
.modal-title {
  margin: 10px 0 20px 20px;
}
</style>
<div class="list col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main relative">
	<h2 class="no-margin-top margin-bottom">Access Levels</h2>
	<div class="col-sm-12 no-padding-left no-padding-right small-margin-top">
    <div class="default" ng-hide="hidethis" ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error', fade: startFade }" ng-if="flash" ng-bind="flash.message"></div>
		<table class="table table-striped">
			<tr>
				<th>Level</th>
				<th>Description</th>
				<th class="action">Action</th>
			</tr>
			<tr ng-repeat="level in vm.accessLevels">
        <td>{{level.level}}<span class="margin-left" ng-if="level.level == 1">(lowest)</span><span class="margin-left" ng-if="level.level == 5">(highest)</span></td>
        <td>{{level.name}}</td>
        <td class="action">


            <button ng-click="vm.showForm(level.id);" type="button" class="btn btn-default" aria-label="Edit"> 
              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
            </button>               

        </td>
      </tr>
    </table>
	</div>
  <!-- modal form -->
  <script type="text/ng-template" id="modal-form.html">
      <div class="modal-title">
        <h3>Edit Access Level</h3>
      </div>
      <form name="form.accessForm" ng-submit="submitForm()" novalidate>
          <div class="modal-body well margin-left margin-right">
              <div class="form-group" ng-class="{ 'has-error': form.level.$dirty && form.accessLevel.$error.required }">
                  <label for="level">Access Level</label>
                  <input type="text" name="level" id="level" class="form-control" ng-model="vm.accessLevel.level" readonly />
              </div>
              <div class="form-group" ng-class="{ 'has-error': form.name.$dirty && form.name.$error.required }">
                  <label for="name">Description</label>
                  <input type="text" name="name" id="name" class="form-control" ng-model="vm.accessLevel.name" required />
                  <span ng-show="form.name.$dirty && form.name.$error.required" class="help-block">Access name is required</span>
              </div>

          </div>
          <div class="modal-footer">
              <span ng-click="cancel()" class="btn btn-link">Cancel</span>
              <button type="submit" class="btn btn-primary" ng-disabled="form.accessForm.$invalid">Update</button>
          </div>
      </form>
  </script>

</div>