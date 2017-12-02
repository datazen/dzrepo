(function () {
    'use strict';

    angular
        .module('app')
        .controller('StoreAccountController', StoreAccountController);
 
    StoreAccountController.$inject = ['StoreAccountService', '$rootScope'];
    function StoreAccountController(StoreAccountService, $rootScope) {
        var vm = this;

        vm.customer = [];

        initController();

        function initController() {
            //loadCustomerAccount();
        }

        function loadCustomerAccount(id) {
            StoreAccountService.GetByEmailAddress(id)
                .then(function (customer) {
                    vm.customer = customer.data;
                });
        }
        
    }

})();