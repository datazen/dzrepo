    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

      <!-- Top Menu Items -->
      <div class="btn-group float-right" uib-dropdown is-open="status.isopen">
        <!-- button id="single-button" type="button" class="btn btn-black" uib-dropdown-toggle ng-disabled="disabled" -->
            
        <div class="header-avatar" uib-dropdown-toggle ng-disabled="disabled">    
          <div class="header-avatar_img-container">
            <img class="header-avatar_img img-circle" src="img/{{vm.user.avatar}}" alt="{{vm.user.firstName}} {{vm.user.lastName}}">
          </div>
        </div>

        <!-- /button -->
        <ul class="dropdown-menu" uib-dropdown-menu role="menu" aria-labelledby="single-button">
          <li role="menuitem" class="margin-left">{{vm.user.firstName}} {{vm.user.lastName}} <br />@{{vm.user.username}}</li>
          <li role="separator" class="divider"></li>
          <li role="menuitem"><a ng-href="#!/profile"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Profile</a></li>
          <li role="separator" class="divider"></li>
          <li role="menuitem"><a ng-href="#!/login"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Logout</a></li>
        </ul>
      </div>

      <!-- div class="float-right margin-top header-login-text margin-right"><span class="header-username">{{vm.user.username}}</span> | <a href="#!/login">Logout</a></div -->

      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">

        <button type="button" class="navbar-toggle pull-left no-margin-right" ng-init="navCollapsed = true" ng-click="navCollapsed = !navCollapsed">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Angularjs Admin</a>
      </div>
    
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse margin-left" ng-class="!navCollapsed && 'in'" ng-click="navCollapsed=true">     
        <ul class="nav navbar-nav visible-xs">
            <li><span class="lead nav-title">Checks</span>
            <li><a ng-href="#!/newCheck" class="active">New Check</a></li>
            <li><a ng-href="#!/listCheckss">List Checks</a></li>
            <li><a ng-href="#!/miscChecks">Miscellaneous Checks</a></li>
            <li><a ng-href="#!/checkSetup">Check Setup</a></li>  
            <li><span class="lead nav-title">Company</span>
            <li><a ng-href="#!/companySetup" class="active">Setup</a></li>
            <li><a ng-href="#!/companyTaxes">Taxes</a></li>
            <li><a ng-href="#!/companyDeductions">Deductions</a></li>
            <li><a ng-href="#!/companyWages">Wages</a></li>
            <li><span class="lead nav-title">Employees</span>
            <li><a ng-href="#!/listEmployees" class="active">List</a></li>
            <li><a ng-href="#!/addEmployee">Add New</a></li>
            <li><span class="lead nav-title">Forms & Reports</span>
            <li><a ng-href="#!/forms" class="active">Forms</a></li>
            <li><a ng-href="#!/reports">Reports</a></li>            
        </ul>
      </div><!-- /.navbar-collapse -->
    </nav>


<!-- nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
    <div class="float-right margin-top header-login-text"><span class="header-username">{{vm.user.username}}</span> | <a href="#!/login">Logout</a></div>

		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">My Secure Payroll</a>
		</div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#">Dashboard</a></li>

            <li role="separator" class="divider"></li>
            <li><a href="#!/companySetup">Company Setup</a></li>
            <li><a href="#!/companyTaxes">Tax Setup</a></li>
            <li><a href="#!/companyDeductions">Deductions Setup</a></li>
            <li><a href="#!/companyWages">Wages Setup</a></li>
            <li role="separator" class="divider"></li>

      </ul>

  </div>
    </div>
</nav --> 