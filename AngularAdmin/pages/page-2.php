<?php
if (file_exists('../inc/header.inc.php')) include '../inc/header.inc.php';
if (file_exists('../inc/sidebar.inc.php')) include '../inc/sidebar.inc.php';
?> 

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<h1 class="page-header no-margin-top">Page 2</h1>
	<div class="col-sm-12 no-padding-left">
		<div ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error' }" ng-if="flash" ng-bind="flash.message"></div>

		<div class="well">
			<h1>Hi {{vm.user.firstName}}!</h1>
			<p>You're logged in!!</p>
			<p><a href="#!/login" class="btn btn-primary">Logout</a></p>
		</div>
	</div>
</div>