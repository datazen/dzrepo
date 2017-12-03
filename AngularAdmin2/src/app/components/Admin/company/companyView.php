<?php
if (file_exists('../../../../app/shared/Admin/header/headerView.php')) include '../../../../app/shared/Admin/header/headerView.php';
if (file_exists('../../../../app/shared/Admin/sidebar/sidebarView.php')) include '../../../../app/shared/Admin/sidebar/sidebarView.php';
?> 
<div class="list col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main relative">
	<h2 class="no-margin-top margin-bottom">My Company</h2>
	<div class="col-sm-12 no-padding-left no-padding-right small-margin-top">
    <div class="default" ng-hide="hidethis" ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error', fade: startFade }" ng-if="flash" ng-bind="flash.message"></div>
    
	</div>
</div>