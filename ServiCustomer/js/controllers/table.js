angular.module('servi.controllers.table', [])

.controller('table', function(
    $scope, $rootScope, $http, $location, $uibModal, $routeParams, $window,
    Restaurant, Feedback, Analytics, Requests, Categories
) {
    var push = function(data) { };

    $scope.init = function() {
        Restaurant.checkState().
        then(function(success){
            if(success) {
                Requests.init(Restaurant.table.id);

                $scope.restaurant = Restaurant;
                $scope.categories = Categories.getAll();
            }
        });
    },

    $scope.logout = function() {
        //Restaurant.logout();
        $location.path("/feedback-stars");
    },

    $scope.openMenu = function() {
        $scope.restaurant.goToMenu();
    };

    $scope.openSpecial = function() {
        $scope.restaurant.goToSpecial();

        var linkAction = $scope.restaurant.getAction('link');
        if( linkAction != null ) {
            $window.open(linkAction.settings, '_blank');
        }
    }

    $scope.callWaiter = function() {
        Analytics.trackEvent('menu', 'call', 'waiter');
        $scope.execCallRequest('call', "Calling your server");
    },
    $scope.callRefill = function() {
        Analytics.trackEvent('menu', 'call', 'refill');
        $scope.execCallRequest('refill', "Reordering drinks");
    },
    $scope.callCheck = function() {
        Analytics.trackEvent('menu', 'call', 'check');
        $scope.execCallRequest('check', "Asking for a check");
    };
    $scope.showRequestBillScreen = function() {
        Restaurant.goToRequestBill();
    };

    $scope.showFeaturedProductsScreen = function(category) {  
        Restaurant.goToRequestFeaturedProducts(category); 
    };

    $scope.saveCSAT = function(value) {
        Analytics.trackEvent('menu', 'feedback', 'csat');
        Feedback.saveCSAT(value);
    },

    $scope.execCallRequest = function(callType, friendlyCallType) {
        var notification = Requests.request(callType);
        Restaurant.goToNotification(notification);
    },

    $scope.menu = function(callType) {
        Restaurant.goToMenu();
    },

    $scope.openRefillModal = function () {
        var modalInstance = $uibModal.open({
          animation: false,
          templateUrl: 'refillModal.html',
          controller: 'refillModal',
          size: 'lg',
          resolve: { }
        });

        modalInstance.result.then(function () {
            $scope.callRefill();
        }, function () {
            //$log.info('Modal dismissed at: ' + new Date());
        });
    };
})
.controller('refillModal', function ($scope, $uibModalInstance) {

  $scope.order = function () {
      $uibModalInstance.close();
  };

  $scope.dismiss = function () {
      $uibModalInstance.dismiss();
  };
});
