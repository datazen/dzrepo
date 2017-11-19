<?php
if (file_exists('../inc/header.inc.php')) include '../inc/header.inc.php';
if (file_exists('../inc/sidebar.inc.php')) include '../inc/sidebar.inc.php';
?> 

<div class="list col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main relative">
	<h2 class="no-margin-top margin-bottom">User List</h2>
  <button ng-click="go('/addUser')" class="list__add-button btn btn-success btn-sm"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
	<div class="col-sm-12 no-padding-left no-padding-right small-margin-top">
    <div class="default" ng-hide="hidethis" ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error', fade: startFade }" ng-if="flash" ng-bind="flash.message"></div>

		<table class="table table-striped">
			<tr>
				<th>ID</th>
				<th>First Name</th>
				<th>Last Name</th>
				<th class="action">Action</th>
			</tr>
			<tr ng-repeat="user in vm.allUsers">
        <td>{{user.id}}</td>
        <td>{{user.firstName}}</td>
        <td>{{user.lastName}}</td>
        <td class="action">
       	  <button ng-click="vm.editUserRecord(user.id);" type="button" class="btn btn-default" aria-label="Edit"> 
            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
          </button>
          <button class="btn btn-sm btn-danger" type="button" aria-label="Delete" ng-confirm-click="Are you sure you want to delete this record?" confirmed-click="vm.deleteUser(user.id);">
            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
          </button>                  
        </td>
      </tr>
    </table>

	</div>
</div>