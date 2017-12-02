(function () {
    'use strict';

    angular
        .module('app')
        .controller('HeaderCtrl', HeaderCtrl)
        .directive('passwordVerify', passwordVerify);

    HeaderCtrl.$inject = ['ConfigurationService', 'AccessService', '$rootScope', '$scope'];
    function HeaderCtrl(ConfigurationService, AccessService, $rootScope, $scope) {
        var vm = this;

        initController();

        function initController() {
            loadConfigurationValues();
            loadPageAccess();
        }

        function loadConfigurationValues() {
            ConfigurationService.GetAllConfigurationData()
                .then(function (config) {

                    var json = "{";
                    angular.forEach(config.data , function(value, key) {
                        json += '"' + value.key + '" : "' + value.value + '",';
                    });
                    json = json.slice(0,-1);
                    json += "}";

                    var configs = eval('(' + json + ')');

                    $rootScope.globals.config = configs;
                    
                });
        }  

        function loadPageAccess() {
            AccessService.GetPagesByAccessLevel($rootScope.globals.currentUser.accessLevel)
                .then(function (pages) {

                    var json = "[";
                    angular.forEach(pages.data , function(value, key) {
                        json += '"' + value.page + '",';
                    });
                    json = json.slice(0,-1);
                    json += "]";

                    var access = eval('(' + json + ')');

                    $rootScope.globals.currentUser.pageAccess = access;
                });
        }

        $scope.hasAccess = function(page) {
            return ($.inArray(page, $rootScope.globals.currentUser.pageAccess) !== -1) ? true : false;
        }        

    }

    function passwordVerify() {
        return {
          restrict: 'A', // only activate on element attribute
          require: '?ngModel', // get a hold of NgModelController
          link: function(scope, elem, attrs, ngModel) {
            if (!ngModel) return; // do nothing if no ng-model

            // watch own value and re-validate on change
            scope.$watch(attrs.ngModel, function() {
              validate();
            });

            // observe the other value and re-validate on change
            attrs.$observe('passwordVerify', function(val) {
              validate();
            });

            var validate = function() {
              // values
              var val1 = ngModel.$viewValue;
              var val2 = attrs.passwordVerify;


              // set validity
              if ((val1 == undefined || val1 == '') && (val2 == undefined || val2 == '')) { // blank is valid
                ngModel.$setValidity('passwordVerify', true);
              } else {
                ngModel.$setValidity('passwordVerify', val1 == val2);
              }
            };
          }
        }
    }     

})();