(function () {
    'use strict';

    angular
        .module('app')
        .controller('AdminSidebarController', AdminSidebarController);         

    AdminSidebarController.$inject = ['AdminConfigurationService', '$rootScope', '$scope', '$location'];
    function AdminSidebarController(AdminConfigurationService, $rootScope, $scope, $location) {

        $scope.configurationGroups = [];
        $scope.isCollapsedMain = true;
        $scope.isCollapsedAccess = true;
        $scope.isCollapsedConfig = true;

        initController();
        
        function initController() {
            loadConfigurationGroups();
        }

        $rootScope.isCurrentPath = $location.path(); 

        if (($rootScope.isCurrentPath.indexOf('accessLevels')>-1) ||
            ($rootScope.isCurrentPath.indexOf('pageAccess')>-1)) {
    
            $scope.isCollapsedAccess = false;
        }

        if (($rootScope.isCurrentPath.indexOf('configuration')>-1) ||
            ($rootScope.isCurrentPath.indexOf('configuration')>-1)) {
    
            $scope.isCollapsedConfig = false;
        }        

		$scope.go = function ( path ) {
		  $location.path( path );
		};    

        if (($rootScope.isCurrentPath.indexOf('company')>-1) ||
            ($rootScope.isCurrentPath.indexOf('users')>-1) ||
            ($rootScope.isCurrentPath.indexOf('addUser')>-1) ||
            ($rootScope.isCurrentPath.indexOf('editUser')>-1) ||
            ($rootScope.isCurrentPath.indexOf('profile')>-1) ||
            ($rootScope.isCurrentPath.indexOf('accessLevels')>-1) ||
            ($rootScope.isCurrentPath.indexOf('pageAccess')>-1) ||
            ($rootScope.isCurrentPath.indexOf('configuration')>-1)) {
        } else {
            $scope.menuToggle = true;
        }

        function loadConfigurationGroups() {
            AdminConfigurationService.GetConfigurationGroups()
                .then(function (groups) {
                    $scope.configurationGroups = groups.data;                    
                });
        }

        $scope.hasAccess = function(page) {
            return ($.inArray(page, $rootScope.globals.currentUser.pageAccess) !== -1) ? true : false;
        }        
    }

})();