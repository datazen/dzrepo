(function () {
    'use strict';

    angular
    .module('app')
    .controller('SidebarCtrl', function($scope, $location, $rootScope) {
 
        $scope.isNavCollapsed = true;
        $scope.isCollapsedMain = true;
        $scope.isCollapsedConfig = true;

        $scope.items = [];

		$scope.go = function ( path ) {
		  $location.path( path );
		};

        $rootScope.isCurrentPath = $location.path(); 

        if (($rootScope.isCurrentPath.indexOf('users')>-1) ||
            ($rootScope.isCurrentPath.indexOf('addUser')>-1) ||
            ($rootScope.isCurrentPath.indexOf('editUser')>-1) ||
            ($rootScope.isCurrentPath.indexOf('profile')>-1) ||
            ($rootScope.isCurrentPath.indexOf('accessLevels')>-1) ||
            ($rootScope.isCurrentPath.indexOf('editAccessLevel')>-1) ||
            ($rootScope.isCurrentPath.indexOf('pageAccess')>-1)) {
        } else {
            $scope.menuToggle = true;
        }


    });
})(); 