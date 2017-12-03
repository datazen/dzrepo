<?php
if (file_exists('../../../../app/shared/Admin/header/headerView.php')) include '../../../../app/shared/Admin/header/headerView.php';
if (file_exists('../../../../app/shared/Admin/sidebar/sidebarView.php')) include '../../../../app/shared/Admin/sidebar/sidebarView.php';
?> 
<style>
.action { text-align: right; }
.half { width:30%; }
</style>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<h2 class="no-margin-top margin-bottom">Add User</h2>
	<div class="col-sm-12 no-padding-left no-padding-right">
        <div class="default" ng-hide="hidethis" ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error', fade: startFade }" ng-if="flash" ng-bind="flash.message"></div>
 	        <form novalidate name="form" ng-submit="vm.addUser()" role="form">

                <?php if (file_exists('../../../../app/components/Admin/users/userFormPartial.php')) include('../../../../app/components/Admin/users/userFormPartial.php'); ?>

			</form>
		</div>
	</div>
</div>
<script>
$( document ).ready(function() {
	// set dropdowns to first value
    $('select option:nth-child(1)').prop("selected", true).change();
});
</script>