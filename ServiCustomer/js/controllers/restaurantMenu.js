/* Controllers */
angular.module('servi.controllers.restaurantMenu', [])

.controller('restaurantMenu', function($scope, $routeParams, Restaurant) {

    $scope.restaurant = Restaurant;
    $scope.menuUrl = null;
    $scope.init = function() {
        Restaurant.checkState().then(function(success){
            if(success) {
                $scope.restaurant.restaurant.actions.forEach(function(action){
                    if(action.action === 'menu') {
                        $scope.menuUrl = action.settings;
                    }
                });
            }
        })
    },

    $scope.back = function() {
        Restaurant.goToTable();
    };
});
