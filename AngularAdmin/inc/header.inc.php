  <div ng-controller="HeaderCtrl">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

      <!-- Top Menu Items -->
      <div class="btn-group float-right" uib-dropdown is-open="status.isopen">
        <!-- button id="single-button" type="button" class="btn btn-black" uib-dropdown-toggle ng-disabled="disabled" -->
            
        <div class="header-avatar" uib-dropdown-toggle ng-disabled="disabled">    
          <div class="header-avatar_img-container">
            <img class="header-avatar_img img-circle" src="img/{{((globals.currentUser.avatar) ? globals.currentUser.avatar : 'na.png')}}" alt="{{globals.currentUser.firstName}} {{globals.currentUser.lastName}}">
          </div>
        </div>

        <!-- Profile -->
        <ul class="dropdown-menu" uib-dropdown-menu role="menu" aria-labelledby="single-button">
          <li role="menuitem" class="margin-left">{{globals.currentUser.firstName}} {{globals.currentUser.lastName}} <br />@{{globals.currentUser.username}}</li>
          <li role="separator" class="divider"></li>
          <li role="menuitem"><a ng-href="#!/profile"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Profile</a></li>
          <li role="separator" class="divider"></li>
          <li role="menuitem"><a ng-href="#!/login"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Logout</a></li>
        </ul>
      </div>

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
            <li><span class="lead nav-title">Main Menu</span></li>
            <li ng-if="hasAccess('dashboard')"><a ng-href="#!/dashboard" class="active">Dashboard</a></li>
            <li ng-if="hasAccess('page-1')"><a ng-href="/#!/page-1">Page 1</a></li>
            <li ng-if="hasAccess('page-2')"><a ng-href="/#!/page-2">Page 2</a></li>
            <li ng-if="hasAccess('page-3')"><a ng-href="/#!/page-3">Page 3</a></li>
            <li ng-if="hasAccess('page-4')"><a ng-href="/#!/page-4">Page 4</a></li>
            <li><span class="lead nav-title">Settings Menu</span></li>
            <li ng-if="hasAccess('profile')"><a ng-href="#!/profile">My Profile</a></li>
            <li ng-if="hasAccess('users')"><a ng-href="#!/users">Users</a></li>
            <li ng-if="hasAccess('accessLevels')"><a ng-href="#!/accessLevels">Access Levels</a></li>
            <li ng-if="hasAccess('pageAccess')"><a ng-href="#!/pageAccess">Page Access</a></li>
            <li ng-if="hasAccess('configuration')"><a ng-href="#!/configuration">Configuration</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </nav>
  </div>