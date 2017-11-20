<div class="sidebar col-sm-3 col-md-2" ng-controller="SidebarCtrl">

  <div class="sidebar-title relative">
    <h4 class="sidebar-title__name"><span ng-show="menuToggle" ng-hide="!menuToggle">My Company</span><span ng-show="!menuToggle" ng-hide="menuToggle">Configuration</span></h4>
    <button ng-click="menuToggle = !menuToggle" class="sidebar-title__button btn btn-xs" ng-class="menuToggle ? 'btn-success' : 'btn-primary'">
      <span class="sidebar-title__button-glyph glyphicon" ng-class="menuToggle ? 'glyphicon-cog' : 'glyphicon-home'" aria-hidden="true"></span>
    </button>
  </div>

	<ul class="sidebar__main-menu nav sidebar-nav" ng-show="menuToggle">
    <li class="sidebar__menu_dropdown">
      <button type="button" class="sidebar-menu_button btn btn-black {{isCurrentPath.indexOf('dashboard')>-1 ? 'active' : '' }}" ng-click="go('/dashboard')"><span class="glyphicon glyphicon-dashboard small-margin-right" aria-hidden="true"></span> Dashboard <span class="sr-only">(current)</span></button>
      <button type="button" class="sidebar-menu_button btn btn-black" ng-click="isCollapsedMain = !isCollapsedMain"><span class="glyphicon glyphicon-globe small-margin-right" aria-hidden="true"></span> Menu 1 <span ng-class="isCollapsedMain ? 'glyphicon-menu-right' : 'glyphicon-menu-down'" class="glyphicon pull-right small-margin-top"></span></button>
      <div uib-collapse="{{isCurrentPath.indexOf('page')>-1 ? '' : 'isCollapsedMain' }}" >
        <ul class="sidebar__main-menu_button_list" role="menu">
          <li class="{{isCurrentPath.indexOf('page-1')>-1 ? 'selected' : '' }}" ng-click="go('/page-1')"><a ng-href="/#!/page-1"><span class="glyphicon glyphicon-dashboard small-margin-right" aria-hidden="true"></span> Page 1</a></li>
          <li class="{{isCurrentPath.indexOf('page-2')>-1 ? 'selected' : '' }}" ng-click="go('/page-2')"><a ng-href="/#!/page-2"><span class="glyphicon glyphicon-dashboard small-margin-right" aria-hidden="true"></span> Page 2</a></li>
          <li class="{{isCurrentPath.indexOf('page-3')>-1 ? 'selected' : '' }}" ng-click="go('/page-3')"><a ng-href="/#!/page-3"><span class="glyphicon glyphicon-dashboard small-margin-right" aria-hidden="true"></span> Page 3</a></li>
          <li class="{{isCurrentPath.indexOf('page-4')>-1 ? 'selected' : '' }}" ng-click="go('/page-4')"><a ng-href="/#!/page-4"><span class="glyphicon glyphicon-dashboard small-margin-right" aria-hidden="true"></span> Page 4</a></li>
        </ul>
      </div>
    </li> 
	</ul>

  <ul class="sidebar__settings-menu nav sidebar-nav" ng-show="!menuToggle">
    <li class="sidebar__menu_dropdown">
      <button type="button" style="{{(isCurrentPath.indexOf('users')>-1 || isCurrentPath.indexOf('User')>-1) ? 'color:white; background-color:#449d44;' : '' }}" class="sidebar-menu_button btn btn-black" ng-click="go('/users')"><span class="glyphicon glyphicon-th-list small-margin-right" aria-hidden="true"></span> Users <span class="sr-only">(current)</span></button>
      <button type="button" style="{{isCurrentPath.indexOf('profile')>-1 ? 'color:white; background-color:#449d44;' : '' }}" class="sidebar-menu_button btn btn-black" ng-click="go('/profile')"><span class="glyphicon glyphicon-user small-margin-right" aria-hidden="true"></span> My Profile <span class="sr-only">(current)</span></button>
      <button type="button" style="{{isCurrentPath.indexOf('settings')>-1 ? 'color:white; background-color:#449d44;' : '' }}" class="sidebar-menu_button btn btn-black" ng-click="isCollapsedConfig = !isCollapsedConfig"><span class="glyphicon glyphicon-wrench small-margin-right" aria-hidden="true"></span> Settings <span ng-class="isCollapsedConfig ? 'glyphicon-menu-right' : 'glyphicon-menu-down'" class="glyphicon pull-right small-margin-top"></span></button>
      <div uib-collapse="{{(isCurrentPath.indexOf('accessLevels')>-1 || isCurrentPath.indexOf('editAccessLevel')>-1 || isCurrentPath.indexOf('pageAccess')>-1) ? '' : 'isCollapsedConfig' }}" >
        <ul class="sidebar__main-menu_button_list" role="menu">
          <li style="{{(isCurrentPath.indexOf('accessLevels')>-1 || isCurrentPath.indexOf('editAccessLevel')>-1) ? 'color:white; background-color:#449d44 !important;' : '' }}" class="{{isCurrentPath.indexOf('accessLevels')>-1 ? 'selected' : '' }}" ng-click="go('/accessLevels')"><a ng-href="/#!/accessLevels"><span class="glyphicon glyphicon-lock small-margin-right" aria-hidden="true"></span> Access Levels</a></li>
          <li style="{{isCurrentPath.indexOf('pageAccess')>-1 ? 'color:white; background-color:#449d44 !important;' : '' }}" class="{{isCurrentPath.indexOf('pageAccess')>-1 ? 'selected' : '' }}" ng-click="go('/pageAccess')"><a ng-href="/#!/pageAccess"><span class="glyphicon glyphicon-file small-margin-right" aria-hidden="true"></span> Page Access</a></li>
        </ul>
      </div>
    </li> 
  </ul>  


</div>