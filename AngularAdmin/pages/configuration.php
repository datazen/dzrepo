<?php
if (file_exists('../inc/header.inc.php')) include '../inc/header.inc.php';
if (file_exists('../inc/sidebar.inc.php')) include '../inc/sidebar.inc.php';
?> 

<div class="list col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main relative">
  <h2 class="no-margin-top margin-bottom">Configuration</h2>
  <div class="col-sm-12 no-padding-left no-padding-right small-margin-top">
    <div class="default" ng-hide="hidethis" ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error', fade: startFade }" ng-if="flash" ng-bind="flash.message"></div>
    <table class="table table-striped">
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Key</th>
        <th>Value</th>
        <th class="action">Action</th>
      </tr>
      <tr ng-repeat="config in vm.configurations | startFrom:currentPage*pageSize | limitTo:pageSize">
        <td>{{config.id}}</td>
        <td>{{config.title}}</td>
        <td>{{config.key}}</td>
        <td>{{config.value}}</td>
        <td class="action">
            <button ng-click="vm.showForm(config.id);" type="button" class="btn btn-default" aria-label="Edit"> 
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



             
          </div>
          <div class="modal-footer">
              <span ng-click="cancel()" class="btn btn-link">Cancel</span>
              <button type="submit" class="btn btn-primary" ng-disabled="form.configuration.$invalid">Update</button>
          </div>
      </form>
  </script>

</div>