(function () {
    'use strict';

    angular
        .module('app')
        .controller('StoreHomeController', StoreHomeController);

    StoreHomeController.$inject = ['$rootScope', '$location'];
    function StoreHomeController($rootScope, $location) {
        var vm = this;

        initController();

        function initController() {
        }

    }
})();