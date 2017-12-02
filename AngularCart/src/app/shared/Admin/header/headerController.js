(function () {
    'use strict';

    angular
        .module('app')
        .controller('AdminHeaderController', AdminHeaderController);
        
    AdminHeaderController.$inject = ['AdminConfigurationService', 'AdminPageAccessService', '$rootScope', '$scope'];
    function AdminHeaderController(AdminConfigurationService, AdminPageAccessService, $rootScope, $scope) {
        var vm = this;

        initController();

        function initController() {
            loadConfigurationValues();
            loadPageAccess();
        }

        function loadConfigurationValues() {
            AdminConfigurationService.GetAllConfigurationData()
                .then(function (config) {
                    var jsonString = JSON.stringify(config.data);
                    var configs = JSON.parse(jsonString);
                    $rootScope.globals.config = configs;                   
                });
        }       

        function loadPageAccess() {
            AdminPageAccessService.GetPagesByAccessLevel($rootScope.globals.currentUser.accessLevel)
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