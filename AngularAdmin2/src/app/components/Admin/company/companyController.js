(function () {
    'use strict';

    angular
        .module('app')
        .controller('AdminCompanyController', AdminCompanyController);   

    AdminCompanyController.$inject = ['AdminCompanyService', 'AdminFlashService', '$rootScope', '$scope', '$location', '$timeout'];
    function AdminCompanyController(AdminCompanyService, AdminFlashService, $rootScope, $scope, $location, $timeout) {
        var vm = this;

        vm.customer = null;

        initController();

        function initController() {
            loadCompany();
        }

        function loadCompany() {
            AdminCompanyService.GetByEmail($rootScope.globals.currentUser.username)
                .then(function (customer) {
                    vm.customer = customer.data;
                });
        }
        
    }

})();