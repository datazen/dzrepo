angular.module('servi.controllers.requestProducts', [])

.controller('requestProducts', function(
    $scope, $rootScope, $http, $location, $uibModal, $routeParams, $window,
    Restaurant, Feedback, Analytics, Requests
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

    //TODO: replace below logic with product data from web service
    $scope.category = window.location.href.substr(window.location.href.lastIndexOf('/') + 1);
    $scope.products = [];
    switch($scope.category){
        case 'cocktails':
            $scope.title = 'Featured Cocktails';
            $scope.products = [{"id":1,"name":"Martini Bianco, Rosso, Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails1.png"},
                               {"id":2,"name":"Martini Royale","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails2.png"},
                               {"id":3,"name":"Martini Royale Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails3.png"},
                               {"id":4,"name":"Smirnoff Vodka","blurb":"Smirnoff Triple Distilled, Rasberry, Vanilla Vodka","image":"cocktails4.png"},
                               {"id":5,"name":"Martini Bianco, Rosso, Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails1.png"},
                               {"id":6,"name":"Martini Royale","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails2.png"},
                               {"id":7,"name":"Martini Royale Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails3.png"},
                               {"id":8,"name":"Smirnoff Vodka","blurb":"Smirnoff Triple Distilled, Rasberry, Vanilla Vodka","image":"cocktails4.png"}];                               
            break;
        case 'beers':
            $scope.title = 'Featured Beers';
            $scope.products = [{"id":1,"name":"Martini Bianco, Rosso, Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails1.png"},
                               {"id":2,"name":"Martini Royale","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails2.png"}];            
            break;
        case 'wines':
            $scope.title = 'Featured Wines';
            $scope.products = [{"id":1,"name":"Martini Bianco, Rosso, Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails1.png"},
                               {"id":2,"name":"Martini Royale","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails2.png"},
                               {"id":3,"name":"Martini Royale Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails3.png"},
                               {"id":4,"name":"Smirnoff Triple Distilled","blurb":"Smirnoff Triple Distilled, Rasberry, Vanilla Vodka","image":"cocktails4.png"}];            
            break;
        case 'beverages':
            $scope.title = 'Featured Beverages';
            $scope.products = [{"id":1,"name":"Martini Bianco, Rosso, Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails1.png"},
                               {"id":2,"name":"Martini Royale","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails2.png"},
                               {"id":3,"name":"Martini Royale Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails3.png"},
                               {"id":4,"name":"Smirnoff Triple Distilled","blurb":"Smirnoff Triple Distilled, Rasberry, Vanilla Vodka","image":"cocktails4.png"}];  
            break;
    }

    $scope.goBack = function() {
      window.history.back();
    };

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
           // $scope.callRefill();
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