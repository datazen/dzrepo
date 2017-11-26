<?php
if (file_exists('../inc/header.inc.php')) include '../inc/header.inc.php';
if (file_exists('../inc/sidebar.inc.php')) include '../inc/sidebar.inc.php';
?> 

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<h1 class="page-header no-margin-top small-margin-bottom">Dashboard</h1>
	<div class="col-sm-12 no-padding-left">
		<div ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error' }" ng-if="flash" ng-bind="flash.message"></div>
            <h3 class="no-margin-top">Globals</h3>

<div>
	Listings per Page: {{globals.config.PAGINATION_LENGTH}}
</div>

            <div ng-repeat="(key, global) in globals">
            	<div class="col-sm-6">
            	<div class="well">
            	  <span class="lead">{{key}}</span>
            	  <ul>
					<li ng-repeat="(k, v) in global">
	                  {{k}} = {{v}}
	  			    </li>
  			      </ul>
			    </div>
			    </div>
			</div>



	</div>
	<p>&nbsp;</p>
	<p class="align-right large-margin-right"><a href="#!/login" class="btn btn-primary">Logout</a></p>
</div>