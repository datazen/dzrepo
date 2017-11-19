angular.module('servi.controllers.feedbackStars', [])
.controller('feedbackStars', function($scope, $http, $location, Restaurant, $routeParams, Analytics, Feedback) {

    $scope.restaurant = Restaurant;
    $scope.feedback = Feedback;

    $scope.waiterRating = 0;
    $scope.serviRating = 0;
    $scope.action = "skip";
    $scope.init = function() {
        Restaurant.checkState();
    };

    $scope.saveWaiterRating = function() {
        console.log('Saving waiter rating of: ' + $scope.waiterRating);
        Feedback.saveWaiterRating($scope.waiterRating );
        $scope.action = "submit";

    };

    $scope.saveServiRating = function() {
        console.log('Saving servi rating of: ' + $scope.serviRating );
        Feedback.saveServiRating($scope.serviRating );
        $scope.action = "submit";
    };

    $scope.done = function(stars) {
        if($scope.action === "skip") {
            Restaurant.logout();
        } else {
            if($scope.serviRating <= 3){
                $location.path("/feedback-text");
            } else {
                $location.path("/feedback-online");
            }
        }
    };



});
