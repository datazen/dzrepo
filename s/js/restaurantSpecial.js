/* Controllers */
angular.module('servi.special.controllers.restaurantSpecial', [])

.controller('restaurantSpecial', function($scope, $routeParams, $sce, Restaurant) {

    $scope.restaurant = Restaurant;

    $scope.init = function() {
        Restaurant.checkState()
        .then(function(success){
            var special = $scope.restaurant.getAction('special');
            console.log(special);
            if( special != null ) {
                $scope.specialUrl = $sce.trustAsResourceUrl(special.settings);
            }
        });
    },

    $scope.back = function() {
        Restaurant.goToTableOverHTTPS();
    };
});
