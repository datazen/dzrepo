<style>
.action { text-align: right; }
.half { width:30%; }
</style>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<h2 class="no-margin-top margin-bottom">Edit User</h2>
	<div class="col-sm-12 no-padding-left">
        <div class="default" ng-hide="hidethis" ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error', fade: startFade }" ng-if="flash" ng-bind="flash.message"></div>
 	        <form novalidate name="form" ng-submit="vm.updateAdminUser()" role="form">
                
                <ng-include src="'./app/components/Admin/users/userFormPartial.php'"></ng-include>

			</form>
		</div>
	</div>
</div>