angular.module('servi.controllers.requestBill', [])

.controller('requestBill', function(
    $scope, $rootScope, $http, $location, $uibModal, $routeParams, $window,
    Restaurant, Feedback, Analytics, Requests
) {
    var paymentMethod = 0,
        tipLimits = {
            min: 0,
            max: 20
        };

    $scope.restaurant = Restaurant;
    $scope.step = 1;
    $scope.tip = 0;
    $scope.tipLimits = tipLimits;

    $scope.init = function() {
        Restaurant.checkState();
                // .then(function (success) {
                //     if (success) {
                //         console.log('restaurant reinitialised');
                //     }
                // });
    };

    $scope.selectPaymentMethod = function ($event) {
        paymentMethod = $event.target.getAttribute('data-value');

        $scope.step = 2;
    };

    $scope.isPaymentButtonActive = function (buttonPaymentMethod) {
        return buttonPaymentMethod == paymentMethod;
    };

    $scope.hasTip = function () {
        return [2, 3].indexOf(parseInt(paymentMethod, 10)) !== -1;
    };

    $scope.cancelBillRequest = function () {
        Restaurant.goToTable();
    };

    $scope.submitBillRequest = function () {
        Analytics.trackEvent('menu', 'call', 'check');

        var notification = Requests.request('check', {payment: paymentMethod, tip: $scope.tip});
        Restaurant.goToNotification(notification);
    };

    $scope.changeTipBy = function (amount) {
        var newTip = $scope.tip + parseInt(amount, 10);

        $scope.validateAndUpdateTipTo(newTip);
    };

    $scope.validateAndUpdateTipTo = function (newTip) {
        newTip = parseInt(newTip, 10);

        if (newTip < tipLimits.min) {
            newTip = tipLimits.min;
        } else if (newTip > tipLimits.max) {
            newTip = tipLimits.max;
        }

        $scope.tip = newTip;
    };
});