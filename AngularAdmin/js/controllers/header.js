(function () {
    'use strict';

    angular
        .module('app')
        .controller('HeaderCtrl', HeaderCtrl); 

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

})();