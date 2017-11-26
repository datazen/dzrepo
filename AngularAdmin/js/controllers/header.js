(function () {
    'use strict';

    angular
        .module('app')
        .controller('HeaderCtrl', HeaderCtrl); 

    HeaderCtrl.$inject = ['UserService', 'ConfigurationService', '$rootScope', '$scope'];
    function HeaderCtrl(UserService, ConfigurationService, $rootScope, $scope) {
        var vm = this;

        $scope.thisUser = null;

        initController();

        function initController() {
            loadCurrentUser();
            loadConfigurationValues();
        }

        function loadCurrentUser() {
            UserService.GetByUsername($rootScope.globals.currentUser.username)
                .then(function (user) {
                    $scope.thisUser = user;
                });
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

    }

})();