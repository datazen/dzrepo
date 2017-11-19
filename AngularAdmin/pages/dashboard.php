<?php
if (file_exists('../inc/header.inc.php')) include '../inc/header.inc.php';
if (file_exists('../inc/sidebar.inc.php')) include '../inc/sidebar.inc.php';
?> 

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<h1 class="page-header no-margin-top">Dashboard</h1>
	<div class="col-sm-12 no-padding-left">
		<div ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error' }" ng-if="flash" ng-bind="flash.message"></div>

		<div class="well">
			<h1>Hi {{vm.user.firstName}}!</h1>
			<p>You're logged in!!</p>
			<div><span class="font-test">1234567890</span></div>
			<div><span class="micr-text">ABCD1234567890</span></div>
			<h3>All registered users:</h3>
			<ul>
				<li ng-repeat="user in vm.allUsers">
					{{user.username}} ({{user.id}} : {{user.firstName}} {{user.lastName}})
					- <a ng-click="vm.deleteUser(user.id)">Delete</a>
				</li>
			</ul>
			<p>&nbsp;</p>
			<p><a href="#!/login" class="btn btn-primary">Logout</a></p>
		</div>
	</div>
</div>