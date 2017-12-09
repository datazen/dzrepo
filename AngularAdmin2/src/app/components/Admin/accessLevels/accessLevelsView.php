<div class="list col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main relative">
	<h2 class="no-margin-top margin-bottom">Access Levels</h2>
	<div class="col-sm-12 no-padding-left no-padding-right small-margin-top">
    <div class="default" ng-hide="hidethis" ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error', fade: startFade }" ng-if="flash" ng-bind="flash.message"></div>
		<table class="table table-striped">
			<tr>
				<th>Level</th>
				<th>Title</th>
				<th class="action">Action</th>
			</tr>
			<tr ng-repeat="level in vm.accessLevels">
        <td>{{level.level}}<span class="margin-left" ng-if="level.level == 0">(lowest)</span><span class="margin-left" ng-if="level.level == 5">(highest)</span></td>
        <td>{{level.title}}</td>
        <td class="action">
            <button ng-click="vm.showForm(level.id);" type="button" class="btn btn-sm btn-primary" aria-label="Edit"> 
              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
            </button>               
        </td>
      </tr>
    </table>
	</div>
  <!-- modal form -->
  <script type="text/ng-template" id="access-modal-form.html">
      <div class="modal-title">
        <h3>Edit Access Level</h3>
      </div>
      <form name="form.accessForm" ng-submit="submitForm()" novalidate>
          <div class="modal-body well margin-left margin-right">
              <div class="form-group" ng-class="{ 'has-error': form.level.$dirty && form.level.$error.required }">
                  <label for="level">Access Level</label>
                  <input type="text" name="level" id="level" class="form-control" ng-model="vm.accessLevel.level" readonly />
              </div>
              <div class="form-group" ng-class="{ 'has-error': form.title.$dirty && form.title.$error.required }">
                  <label for="title">Title</label>
                  <input type="text" name="title" id="title" class="form-control" ng-model="vm.accessLevel.title" required />
                  <span ng-show="form.title.$dirty && form.title.$error.required" class="help-block">Access title is required</span>
              </div>

          </div>
          <div class="modal-footer">
              <span ng-click="cancel()" class="btn btn-link">Cancel</span>
              <button type="submit" class="btn btn-primary" ng-disabled="form.accessForm.$invalid">Update</button>
          </div>
      </form>
  </script>

</div>