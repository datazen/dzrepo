  <div ng-controller="AdminHeaderController">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
<style>
.btn-avatar { width:30%; margin: 6px -60px 0 0; cursor: pointer; }
.btn-avatar-list { margin:6px 0 0 -112px; }
.btn-avatar-list-email { margin-left:8px; }
.img-circle { border: 1px solid #e6e9ed; }
.img-circle.header-avatar_img { width: 36%; background: #fff none repeat scroll 0 0; border: 1px solid rgba(52, 73, 94, 0.44); position: inherit; z-index: 1000; }

/* Extra Small Devices, Phones */ 
@media only screen and (min-width : 480px) {
  .img-circle.header-avatar_img { width: 22%; } 
  .btn-avatar { margin: 4px -136px 0 0; }  
  .btn-avatar-list { margin: 6px 0 0 -118px; }  
}

/* Small Devices, Tablets */
@media only screen and (min-width : 768px) {
  .img-circle.header-avatar_img { width: 17%; }
  .btn-avatar { margin: 6px -183px 0 0; }   
  .btn-avatar-list { margin:6px 0 0 -113px; }

}

/* Medium Devices, Desktops */
@media only screen and (min-width : 992px) {
  .img-circle.header-avatar_img { width: 13%; }
  .btn-avatar { margin: 5px -260px 0 0; }   
  .btn-avatar-list { margin:6px 0 0 -112px; }
}

/* Large Devices, Wide Screens */
@media only screen and (min-width : 1200px) {
  .img-circle.header-avatar_img { width: 11%; }
  .btn-avatar { margin: 5px -331px 0 0; }   
  .btn-avatar-list { margin:6px 0 0 -112px; }

}


</style>

      <!-- Top Menu Items -->
      <div class="btn-avatar btn-group float-right" uib-dropdown is-open="status.isopen">
        <div uib-dropdown-toggle ng-disabled="disabled">    
          <div class="header-avatar_img-container">
            <img class="header-avatar_img img-circle" src="{{globals.config.IMAGES_PATH}}Admin/{{((globals.currentUser.avatar) ? globals.currentUser.avatar : 'na.png')}}" alt="{{globals.currentUser.firstName}} {{globals.currentUser.lastName}}">
          </div>
        </div>

        <!-- Profile -->
        <ul class="btn-avatar-list" uib-dropdown-menu role="menu" aria-labelledby="single-button">
          <li role="menuitem" class="btn-avatar-list-email">{{globals.currentUser.firstName}} {{globals.currentUser.lastName}} <br />{{globals.currentUser.email}}</li>
          <li role="separator" class="divider"></li>
          <li role="menuitem"><a ng-href="#!/Admin/profile"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Profile</a></li>
          <li role="separator" class="divider"></li>
          <li role="menuitem"><a ng-href="#!/Admin/login"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Logout</a></li>
        </ul>
      </div>

      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">

        <button type="button" class="navbar-toggle pull-left no-margin-right small-margin-left" ng-init="navCollapsed = true" ng-click="navCollapsed = !navCollapsed">
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
            <li ng-if="hasAccess('dashboard')"><a ng-href="#!/Admin/dashboard" class="active">Dashboard</a></li>
            <li ng-if="hasAccess('page-1')"><a ng-href="/#!/Admin/page-1">Page 1</a></li>
            <li ng-if="hasAccess('page-2')"><a ng-href="/#!/Admin/page-2">Page 2</a></li>
            <li ng-if="hasAccess('page-3')"><a ng-href="/#!/Admin/page-3">Page 3</a></li>
            <li ng-if="hasAccess('page-4')"><a ng-href="/#!/Admin/page-4">Page 4</a></li>
            <li><span class="lead nav-title">Settings Menu</span></li>
            <li ng-if="hasAccess('profile')"><a ng-href="#!/Admin/profile">My Profile</a></li>
            <li ng-if="hasAccess('users')"><a ng-href="#!/Admin/users">Users</a></li>
            <li ng-if="hasAccess('accessLevels')"><a ng-href="#!/Admin/accessLevels">Access Levels</a></li>
            <li ng-if="hasAccess('pageAccess')"><a ng-href="#!/Admin/pageAccess">Page Access</a></li>
            <li ng-if="hasAccess('configuration')"><a ng-href="#!/Admin/configuration">Configuration</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </nav>
  </div>