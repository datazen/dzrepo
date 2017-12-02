<div class="sidebar col-sm-3 col-md-2" ng-controller="AdminSidebarController">

  <div class="sidebar-title relative">
    <h4 class="sidebar-title__name"><span ng-show="menuToggle" ng-hide="!menuToggle">My Company</span><span ng-show="!menuToggle" ng-hide="menuToggle">Settings</span></h4>
    <button ng-click="menuToggle = !menuToggle" class="sidebar-title__button btn btn-xs" ng-class="menuToggle ? 'btn-success' : 'btn-primary'">
      <span class="sidebar-title__button-glyph glyphicon" ng-class="menuToggle ? 'glyphicon-cog' : 'glyphicon-home'" aria-hidden="true"></span>
    </button>
  </div>

	<ul class="sidebar__main-menu nav sidebar-nav" ng-show="menuToggle">
    <li class="sidebar__menu_dropdown">
      <button ng-if="hasAccess('dashboard')" type="button" class="sidebar-menu_button btn btn-black {{isCurrentPath.indexOf('dashboard')>-1 ? 'active' : '' }}" ng-click="go('/Admin/dashboard')"><span class="glyphicon glyphicon-dashboard small-margin-right" aria-hidden="true"></span> Dashboard <span class="sr-only">(current)</span></button>
      <button type="button" class="sidebar-menu_button btn btn-black" ng-click="isCollapsedMain = !isCollapsedMain"><span class="glyphicon glyphicon-globe small-margin-right" aria-hidden="true"></span> Menu 1 <span ng-class="isCollapsedMain ? 'glyphicon-menu-right' : 'glyphicon-menu-down'" class="glyphicon pull-right small-margin-top"></span></button>
      <div uib-collapse="{{isCurrentPath.indexOf('page')>-1 ? '' : 'isCollapsedMain' }}" >
        <ul class="sidebar__main-menu_button_list">
          <li ng-if="hasAccess('page-1')" class="{{isCurrentPath.indexOf('page-1')>-1 ? 'selected' : '' }}" ng-click="go('/Admin/page-1')"><a ng-href="/#!/Admin/page-1"><span class="glyphicon glyphicon-dashboard small-margin-right" aria-hidden="true"></span> Page 1</a></li>
          <li ng-if="hasAccess('page-2')" class="{{isCurrentPath.indexOf('page-2')>-1 ? 'selected' : '' }}" ng-click="go('/Admin/page-2')"><a ng-href="/#!/Admin/page-2"><span class="glyphicon glyphicon-dashboard small-margin-right" aria-hidden="true"></span> Page 2</a></li>
          <li ng-if="hasAccess('page-3')" class="{{isCurrentPath.indexOf('page-3')>-1 ? 'selected' : '' }}" ng-click="go('/Admin/page-3')"><a ng-href="/#!/Admin/page-3"><span class="glyphicon glyphicon-dashboard small-margin-right" aria-hidden="true"></span> Page 3</a></li>
          <li ng-if="hasAccess('page-4')" class="{{isCurrentPath.indexOf('page-4')>-1 ? 'selected' : '' }}" ng-click="go('/Admin/page-4')"><a ng-href="/#!/Admin/page-4"><span class="glyphicon glyphicon-dashboard small-margin-right" aria-hidden="true"></span> Page 4</a></li>
        </ul>
      </div>
    </li> 
	</ul>

  <ul class="sidebar__settings-menu nav sidebar-nav" ng-show="!menuToggle">
    <li class="sidebar__menu_dropdown">
      <button ng-if="hasAccess('profile')" type="button" class="{{isCurrentPath.indexOf('profile')>-1 ? 'selected-config' : '' }} sidebar-menu_button btn btn-black" ng-click="go('/Admin/profile')"><span class="glyphicon glyphicon-user small-margin-right" aria-hidden="true"></span> My Profile <span class="sr-only">(current)</span></button>
      <button ng-if="hasAccess('users')" type="button" class="{{(isCurrentPath.indexOf('users')>-1 || isCurrentPath.indexOf('User')>-1) ? 'selected-config' : '' }} sidebar-menu_button btn btn-black" ng-click="go('/Admin/users')"><span class="glyphicon glyphicon-th-list small-margin-right" aria-hidden="true"></span> Users <span class="sr-only">(current)</span></button>

      <button ng-hide="!hasAccess('accessLevels') && !hasAccess('pageAccess')" type="button" class="{{isCurrentPath.indexOf('settings')>-1 ? 'selected-config' : '' }} sidebar-menu_button btn btn-black" ng-click="isCollapsedAccess = !isCollapsedAccess"><span class="glyphicon glyphicon-lock small-margin-right" aria-hidden="true"></span> Access <span ng-class="(isCollapsedAccess == true) ? 'glyphicon-menu-right' : 'glyphicon-menu-down'" class="glyphicon pull-right small-margin-top"></span></button>
      <div uib-collapse="{{(isCurrentPath.indexOf('accessLevels')>-1 || isCurrentPath.indexOf('pageAccess')>-1) ? '' : 'isCollapsedAccess' }}" >
        <ul class="sidebar__settings-menu_button_list" role="menu">
          <li ng-if="hasAccess('accessLevels')" class="{{isCurrentPath.indexOf('accessLevels')>-1 ? 'selected-config' : '' }} padding-left" ng-click="go('/Admin/accessLevels')"><a ng-href="/#!/Admin/accessLevels"><span style="font-size:0.8em;" class="glyphicon glyphicon-triangle-right small-margin-right" aria-hidden="true"></span>Access Levels</a></li>
          <li ng-if="hasAccess('pageAccess')" class="{{isCurrentPath.indexOf('pageAccess')>-1 ? 'selected-config' : '' }} padding-left" ng-click="go('/Admin/pageAccess')"><a ng-href="/#!/Admin/pageAccess"><span style="font-size:0.8em;" class="glyphicon glyphicon-triangle-right small-margin-right" aria-hidden="true"></span>Page Access</a></li>
        </ul>
      </div>

      <button ng-hide="!hasAccess('configuration')" type="button" class="sidebar-menu_button btn btn-black" ng-click="isCollapsedConfig = !isCollapsedConfig;"><span class="glyphicon glyphicon-cog small-margin-right" aria-hidden="true"></span> Configuration <span ng-class="isCollapsedConfig ? 'glyphicon-menu-right' : 'glyphicon-menu-down'" class="glyphicon pull-right small-margin-top"></span></button>
      <div uib-collapse="{{(isCurrentPath.indexOf('configuration')>-1) ? '' : 'isCollapsedConfig' }}" >
        <ul class="sidebar__main-menu_button_list" role="menu">
          <li ng-repeat="group in configurationGroups" class="small-padding-left {{isCurrentPath.indexOf('configuration/' + group.id)>-1 ? 'selected-config' : '' }}">
            <a class="sidebar__dropdown_menu-link" ng-href="/#!/Admin/configuration/{{group.id}}">
              <span style="font-size:0.8em;" class="glyphicon glyphicon-triangle-right small-margin-right" aria-hidden="true"></span>{{group.title}}
            </a>
          </li>
        </ul>
      </div>

    </li> 
  </ul>  
</div>