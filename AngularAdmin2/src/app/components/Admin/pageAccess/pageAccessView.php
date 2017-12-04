<div class="list col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main relative">
  <h2 class="no-margin-top margin-bottom">Page Access</h2>
  <div class="col-sm-12 no-padding-left no-padding-right small-margin-top">
    <div class="default" ng-hide="hidethis" ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error', fade: startFade }" ng-if="flash" ng-bind="flash.message"></div>
    <table class="table table-striped">
      <tr>
        <th>Page</th>
        <th>Access Level</th>
        <th class="action">Action</th>
      </tr>
      <tr ng-repeat="page in vm.pages | startFrom:currentPage*pageSize | limitTo:pageSize">
        <td>{{page.page}}</td>
        <td>{{page.level}} - {{page.accessName}}</td>
        <td class="action">
            <button ng-click="vm.showForm(page.id);" type="button" class="btn btn-sm btn-primary" ng-disabled="(page.page == 'login' || page.page == 'restricted')" aria-label="Edit"> 
              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
            </button>               

        </td>
      </tr>
    </table>
    <div class="align-right margin-right" ng-hide="vm.pages.length <= pageSize">
        <button class="btn btn-primary btn-sm" ng-disabled="currentPage == 0" ng-click="currentPage=currentPage-1"><span class="glyphicon glyphicon-chevron-left small-margin-right" aria-hidden="true"></span>Prev</button>
        <span>{{currentPage+1}}/{{numberOfPages()}}</span>
        <button class="btn btn-primary btn-sm" ng-disabled="currentPage >= vm.pages.length/pageSize - 1" ng-click="currentPage=currentPage+1">Next<span class="glyphicon glyphicon-chevron-right small-margin-left" aria-hidden="true"></span></button>
    </div>
  </div>
  <!-- modal form -->
  <script type="text/ng-template" id="page-modal-form.html">
      <div class="modal-title">
        <h3>Edit Page Access</h3>
      </div>
      <form name="form.pageAccess" ng-submit="submitForm()" novalidate>
          <div class="modal-body well margin-left margin-right">
              <div class="form-group" ng-class="{ 'has-error': form.page.$dirty && form.page.$error.required }">
                  <label for="page">Page</label>
                  <input type="text" name="page" id="page" class="form-control" ng-model="vm.page.page" readonly />
              </div>
              <div class="form-group" ng-class="{ 'has-error': form.level.$dirty && form.level.$error.required }">
                <label class="control-label" for="level">Access Level</label>
                    <select  name="level" id="level" class="form-control" ng-model="vm.page.level" required>
                        <option ng-repeat="accessLevel in vm.accessLevels" value="{{accessLevel.level}}">{{accessLevel.level}} - {{accessLevel.name}}</option>
                    </select>
                <span ng-show="form.level.$dirty && form.level.$error.required" class="help-block">Access level is required</span>
              </div>              
          </div>
          <div class="modal-footer">
              <span ng-click="cancel()" class="btn btn-link">Cancel</span>
              <button type="submit" class="btn btn-primary" ng-disabled="form.pageAccess.$invalid">Update</button>
          </div>
      </form>
  </script>

</div>