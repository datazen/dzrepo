(function () {
    'use strict';

    angular
        .module('app')
        .controller('AdminSidebarController', AdminSidebarController);         

    AdminSidebarController.$inject = ['AdminConfigurationService', 'AdminCompanyService', '$rootScope', '$scope', '$location', '$timeout'];
    function AdminSidebarController(AdminConfigurationService, AdminCompanyService, $rootScope, $scope, $location, $timeout) {

        $scope.configurationGroups = [];
        $scope.company = [];
        $scope.isCollapsedMain = true;
        $scope.isCollapsedAccess = true;
        $scope.isCollapsedConfig = true;

        initController();
        
        function initController() {
            loadAdminConfigurationGroups();
            loadCurrentAdminCompany();
            syncMenuDropdowns();
        }
      

		$scope.go = function ( path ) {
		    $location.path( path );
            $timeout(function(){ 
                syncMenuDropdowns();
            }, 200);
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

        function syncMenuDropdowns() {
            if (($rootScope.isCurrentPath.indexOf('accessLevels')>-1) ||
                ($rootScope.isCurrentPath.indexOf('pageAccess')>-1)) {
                $scope.isCollapsedAccess = false;
            } else {
                $scope.isCollapsedAccess = true;                
            }

            if (($rootScope.isCurrentPath.indexOf('configuration')>-1) ||
                ($rootScope.isCurrentPath.indexOf('configuration')>-1)) {
                $scope.isCollapsedConfig = false;
            } else {
                $scope.isCollapsedConfig = true;

            }             
        }

        $scope.getAdminConfigurationGroup = function(id) {
            $rootScope.globals.adminConfigGroupToShow = id;  
            $location.path('/Admin/configuration');
        }

        function loadAdminConfigurationGroups() {
            AdminConfigurationService.GetAllAdminConfigurationGroups({ cID: $rootScope.globals.currentUser.cID })
                .then(function (groups) {
                    $scope.configurationGroups = groups.data;                    
                });
        }

        function loadCurrentAdminCompany() {
            AdminCompanyService.GetAdminCompanyById({ cID: $rootScope.globals.currentUser.cID })
                .then(function (company) {
                    $scope.company = company.data;
                });
        }        

        $scope.hasAccess = function(page) {
            return ($.inArray(page, $rootScope.globals.currentUser.pageAccess) !== -1) ? true : false;
        }        
    }

})();