(function () {
    'use strict';

    angular
        .module('app')
        .controller('DashboardController', DashboardController);

    DashboardController.$inject = ['UserService', '$rootScope', '$location'];
    function DashboardController(UserService, $rootScope, $location) {
        var vm = this;

        initController();

        function initController() {
        }

    }
})();