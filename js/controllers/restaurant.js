/* Controllers */
angular.module('servi.controllers.restaurant', [])

.controller('restaurant', function($scope, $http, $location, $window, $uibModal, $anchorScroll, Restaurant, Analytics, $routeParams) {
    $scope.tableToken;
    $scope.error;
    $scope.restaurant = Restaurant;

    $scope.init = function() {
        Restaurant.checkState();
    },

    $scope.loginToTable = function() {
        console.log('login to table ' + $scope.tableToken);
        Analytics.trackEvent('restaurant-table', 'try', '1');
        $http.post(apiBaseUrl + "/rest/login/table" , { token: $scope.tableToken } )
        .then(function (response) {
            Analytics.trackEvent('restaurant-table', 'success', Restaurant.tableId );
            Restaurant.initTableData(response.data);
            Restaurant.initWaiterData(response.data);
            Restaurant.goToTable();
        }, function (response) {
            $scope.error = 'Incorrect table number';
            Analytics.trackEvent('restaurant-table', 'fail', '1');
            console.log("fail");
            console.log(response);
        });

        $scope.loginInProgress = true;
    };

    $scope.openMap = function () {
        var modalInstance = $uibModal.open({
          animation: true,
          templateUrl: 'mapModal.html',
          controller: 'MapInstanceCtrl',
          size: 'sm',
          resolve: {
            map: function () {
              return $scope.restaurant.restaurant.map;
            }
          }
        });

        modalInstance.result.then(
            function (selectedItem) {
              //$scope.selected = selectedItem;
            }, function () {
              console.log('Modal dismissed at: ' + new Date());
            }
        );
    };
});

angular.module('servi.controllers.restaurant').controller('MapInstanceCtrl', function ($scope, $uibModalInstance, map) {
  $scope.map = map;

  $scope.close = function () {
    $uibModalInstance.dismiss('cancel');
  };
});
