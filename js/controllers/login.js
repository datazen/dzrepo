/* Controllers */
angular.module('servi.controllers.login', [])

.controller('login', function($scope, $http, $location, User, Restaurant, $routeParams, Analytics) {
    $scope.error = null;

    $scope.init = function () {
        User.checkState();
    };

    $scope.searchRestaurantByName = function (query) {
        var autocompleteQuery = apiBaseUrl + "/rest/login/autocomplete?name="+query;
        // if(User.location != null && User.location.coords.latitude != null) {
        //     autocompleteQuery +=
        //         "&lat=" + User.location.coords.latitude +
        //         "&lon=" + User.location.coords.longitude +
        //         "&acc=" + User.location.coords.accuracy
        // };

        return $http.get(autocompleteQuery)
                    .then(function(restaurants) {
                        restaurants.data.forEach(function (restaurant) {
                            // Hack until the admin interface is completed
                            restaurant.hasLogo = [21, 22].indexOf(restaurant.restaurantId) !== -1;
                        });

                        return restaurants.data;
                    })
                    .catch(function (err) {
                        //TODO: record error and handle it with an error message

                        console.error('Error connecting to server (autocomplete)', err);
                        $scope.error = "Error connecting to ser.vi! We're doing our best to fix the issue";
                    });
    };

    $scope.selectRestaurant = function ($model) {
        $scope.name = $model.restaurantName;
        return $scope.loginToRestaurantByName($scope.name);
    };

    $scope.loginToRestaurantByName = function(name) {
        Analytics.trackEvent('restaurant-login', 'try', '1');
        $scope._login({ name: name });
        $scope.loginInProgress = true;
    };

    $scope._login = function(data) {
        $http.post(apiBaseUrl + "/rest/login/restaurant" , data )
        .then(function (response) {
            Restaurant.initRestarauntData(response.data);
            Analytics.trackEvent('restaurant-login', 'success', Restaurant.restaurant.name);
            Restaurant.goToRestaurant();
        }, function () {
            Analytics.trackEvent('restaurant-login', 'fail', '1');
            $scope.error = "Wrong code. Please try again.";
        });
    };
});
