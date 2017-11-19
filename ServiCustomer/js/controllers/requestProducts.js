angular.module('servi.controllers.requestProducts', [])

.controller('requestProducts', function(
    $scope, $rootScope, $http, $location, $uibModal, $routeParams, $window,
    Restaurant, Feedback, Analytics, Requests, Categories, Products
) {
    $scope.restaurant = Restaurant;

    $scope.init = function() {
        Restaurant.checkState();
                // .then(function (success) {
                //     if (success) {
                //         console.log('restaurant reinitialised');
                //     }
                // });
    };

    $scope.goBack = function() {
        Restaurant.goToTable();
    };

    $scope.category = Categories.getWithName(window.location.href.substr(window.location.href.lastIndexOf('/') + 1));

    if (!$scope.category) {
        $scope.goBack();
        return;
    }

    $scope.products = Products.getFromCategory($scope.category.id);

    if (!$scope.products || !$scope.products.length) {
        $scope.goBack();
        return;
    }

    $scope.openOrderProductsModal = function () {
        var modalInstance = $uibModal.open({
            animation: false,
            templateUrl: 'orderProductsModal.html',
            controller: 'orderProductsModal',
            size: 'lg',
            scope: $scope,
            resolve: { }
        });

        modalInstance.result.then(function () {
            // var notification = Requests.request('product', {
            //     product
            // });

        }, function () {
            //$log.info('Modal dismissed at: ' + new Date());
        });
    };
})
.controller('orderProductsModal', function ($scope, $uibModalInstance) {
  $scope.order = function (qty) {
      //TODO: update to send request to waiter
      $uibModalInstance.close();
  };

  $scope.dismiss = function () {
      $uibModalInstance.dismiss();
  };
})
.filter('capitalize', function() {
    return function(input) {
      return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
    }
})
.animation('.keep-scroll', [function () {
    var keepScroll = function(element, leave){
        var elementPos = element.offset().top;
        var scrollPos = document.body.scrollTop;

        if(elementPos < scrollPos){
            var height = element[0].clientHeight;
            if(leave){
                height *= (-1);
            }
            document.body.scrollTop += height;
        }
    };

    return {
        enter: function (element, doneFn) {
            keepScroll(element);
            doneFn();
        },
        leave: function (element, doneFn) {
            keepScroll(element, true);
            doneFn();
        }
    };
}]);