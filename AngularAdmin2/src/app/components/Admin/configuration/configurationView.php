<div class="list col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main relative">
  <h2 class="no-margin-top margin-bottom">Configuration</h2>
  <div class="col-sm-12 no-padding-left no-padding-right small-margin-top">
    <div class="default" ng-hide="hidethis" ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error', fade: startFade }" ng-if="flash" ng-bind="flash.message"></div>
    <table class="table table-striped">
      <tr>
        <th>Title</th>
        <th>Value</th>
        <th class="action">Action</th>
      </tr>
      <tr ng-repeat="config in vm.configurations | startFrom:currentPage*pageSize | limitTo:pageSize">
        <td>{{config.title}}</td>
        <td>{{config.value}}</td>
        <td class="action">
            <button ng-click="vm.showForm(config.id);" type="button" class="btn btn-sm btn-primary" aria-label="Edit"> 
              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
            </button>               
        </td>
      </tr>
    </table>
    <div class="align-right margin-right" ng-hide="vm.configurations.length <= pageSize">
        <button class="btn btn-primary btn-sm" ng-disabled="currentPage == 0" ng-click="currentPage=currentPage-1"><span class="glyphicon glyphicon-chevron-left small-margin-right" aria-hidden="true"></span>Prev</button>
        <span>{{currentPage+1}}/{{numberOfPages()}}</span>
        <button class="btn btn-primary btn-sm" ng-disabled="currentPage >= vm.configuration.length/pageSize - 1" ng-click="currentPage=currentPage+1">Next<span class="glyphicon glyphicon-chevron-right small-margin-left" aria-hidden="true"></span></button>
    </div>
  </div>
  <!-- modal form -->
  <script type="text/ng-template" id="configuration-modal-form.html">
      <div class="modal-title">
        <h3>Edit Configuration</h3>
      </div>
      <form name="form.configurationForm" ng-submit="submitForm()" novalidate>
          <div class="modal-body well margin-left margin-right">
              <div class="form-group" ng-class="{ 'has-error': form.configurationForm.title.$dirty && form.configurationForm.title.$error.required }">
                  <label for="title">Configuration Title</label>
                  <input type="text" name="title" id="title" class="form-control" ng-model="vm.configuration.title" />
              </div> 
              <div class="form-group" ng-class="{ 'has-error': form.configurationForm.description.$dirty && form.configurationForm.description.$error.required }">
                  <label for="description">Configuration Description</label>
                  <input type="text" name="description" id="description" class="form-control" ng-model="vm.configuration.description" />
              </div> 
              <div class="form-group" ng-class="{ 'has-error': form.configurationForm.key.$dirty && form.configurationForm.key.$error.required }">
                  <label for="key">Configuration Key</label>
                  <input type="text" name="key" id="key" class="form-control" ng-model="vm.configuration.key" readonly />
              </div>
              <div class="form-group" ng-class="{ 'has-error': form.configurationForm.value.$dirty && form.configurationForm.value.$error.required }">
                  <label for="value">Configuration Value</label>
                  <input type="text" name="value" id="value" class="form-control" ng-model="vm.configuration.value" />
              </div>                          
          </div>
          <div class="modal-footer">
              <span ng-click="cancel()" class="btn btn-link">Cancel</span>
              <button type="submit" class="btn btn-primary" ng-disabled="form.configuration.$invalid">Update</button>
          </div>
      </form>
  </script>

</div>