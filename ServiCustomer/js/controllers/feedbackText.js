angular.module('servi.controllers.feedbackText', [])
.controller('feedbackText', function($scope, $http, $location, Restaurant, $routeParams, Analytics, Feedback) {

    $scope.restaurant   = Restaurant;
    $scope.waiterRating = Feedback.waiterRating;
    $scope.serviRating  = Feedback.serviRating;
    $scope.action       = "skip";
    $scope.textFeedback = "";

    $scope.init = function() {
        Restaurant.checkState();
    };

    $scope.onChange = function() {
        if( $scope.textFeedback != null && $scope.textFeedback.length != 0 ) {
            $scope.action = 'submit';
        } else {
            $scope.action = 'skip';
        }
    };

    $scope.done = function() {
        if($scope.action === "submit") {
            Feedback.saveTextFeedback($scope.textFeedback);
        } else {
            Restaurant.logout();
        }
    };


});
