(function () {
    'use strict';

    angular
        .module('app')
        .controller('StoreCustomersController', StoreCustomersController)
        .directive('stringToNumber', function() {
          return {
            require: 'ngModel',
            link: function(scope, element, attrs, ngModel) {
              ngModel.$parsers.push(function(value) {
                return '' + value;
              });
              ngModel.$formatters.push(function(value) {
                return parseFloat(value);
              });
            }
          };
        }).directive('ngConfirmClick', [
            function(){
                return {
                    link: function (scope, element, attr) {
                        var msg = attr.ngConfirmClick || "Are you sure?";
                        var clickAction = attr.confirmedClick;
                        element.bind('click',function (event) {
                            if ( window.confirm(msg) ) {
                                scope.$eval(clickAction)
                            }
                        });
                    }
                };
        }])
        .filter('startFrom', function() {
            return function(input, start) {
                start = +start; //parse to int
                return input.slice(start);
            }
        });   

    StoreCustomersController.$inject = ['StoreCustomerService', 'StoreFlashService', '$rootScope', '$scope', '$location', '$timeout'];
    function StoreCustomersController(StoreCustomerService, StoreFlashService, $rootScope, $scope, $location, $timeout) {
        var vm = this;

        vm.customer = null;

        initController();

        function initController() {
            loadCustomer();
        }

        function loadCurrentCustomer() {
            StoreCustomerService.GetByUsername($rootScope.store.currentUser.username)
                .then(function (customer) {
                    vm.customer = customer.data;
                });
        }
        
    }

})();