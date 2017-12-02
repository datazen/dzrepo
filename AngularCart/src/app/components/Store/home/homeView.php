<?php
//if (file_exists('../../../../app/shared/Admin/header/headerView.php')) include '../../../../app/shared/Admin/header/headerView.php';
//if (file_exists('../../../../app/shared/Admin/sidebar/sidebarView.php')) include '../../../../app/shared/Admin/sidebar/sidebarView.php';
?> 
	<div class="col-sm-12 no-padding-left">
		<div ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error' }" ng-if="flash" ng-bind="flash.message"></div>
		<h3 class="no-margin-top">Store Home (state:{{currentState.module}})</h3>

		<div ng-repeat="(key, global) in frontend">
			<div class="col-sm-6">
				<div class="well">
					<span class="lead">{{key}}</span>
					<ul>
						<li ng-repeat="(k, v) in global">{{k}} = {{v}}</li>
					</ul>
				</div>
			</div>
		</div>

	</div>
	<p>&nbsp;</p>
	<p class="align-right large-margin-right"><a href="#!/login" class="btn btn-primary">Logout</a></p>