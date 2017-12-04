<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<h1 class="page-header no-margin-top">Page 4</h1>
	<div class="col-sm-12 no-padding-left">
		<div ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error' }" ng-if="flash" ng-bind="flash.message"></div>

		<div class="well">
			<h1>Hi {{globals.currentUser.firstName}} {{globals.currentUser.lastName}}</h1>
			<p>You're logged in!!</p>
			<p><a href="#!/Admin/login" class="btn btn-primary">Logout</a></p>
		</div>
	</div>
</div>