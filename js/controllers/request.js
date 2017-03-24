angular.module('servi.controllers.request', [])

.controller('request', function(
    $scope, $http, $location, $rootScope,
    $timeout, $interval, $routeParams, $location,
    Restaurant, Requests, Analytics
) {
    $scope.restaurant = Restaurant;
    $scope.activeRequest = Requests.activeRequest;
    $scope.secondsLeft = 0;

    $scope.init = function() {
        //todo: reload
        Restaurant.checkState()
        .then(function(success){
            if(success) {
                Requests.init(Restaurant.table.id);
                Requests.activeRequest = {
                    uuid    : $routeParams.request,
                    callType: $routeParams.callType,
                    status  : $location.search().status,
                    paymentMethod: $location.search().payment
                };
                $scope.activeRequest = Requests.activeRequest;
                console.log(Requests);
            }
        });

        // todo: check if called double
        var subscription = $scope.$on('notification', function(event, data) {
            console.log('got notification broadcast');
            $location.search('status', 'confirmed');
            $scope.startExpirationTimer();
            $scope.$apply();

        });

        $scope.$on("$destroy", function () {
            console.log("destroy all the things");
            subscription();
        });
    }

    $scope.close = function() {
        Restaurant.goToTable();
    },

    $scope.startExpirationTimer = function() {
        var that = this;
        $scope.secondsLeft = 30;
        var stop = $interval(function() {
            $scope.secondsLeft--;
        }, 1000);

        $timeout(function () {
            $interval.cancel(stop);
            $scope.close();
        },  30 * 1000); //30 seconds;
    },

    $scope.requestStatus = function() {
        return $scope.activeRequest ? $scope.activeRequest.status : 'waiting';
    },

    $scope.cancelRequest = function() {
        console.log('cancel request');
    }

});
