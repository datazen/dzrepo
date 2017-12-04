(function () {
    'use strict';

    angular
        .module('app')
        .controller('AdminDashboardController', AdminDashboardController);

    AdminDashboardController.$inject = ['$rootScope', '$location'];
    function AdminDashboardController($rootScope, $location) {
        var vm = this;

        initController();

        function initController() {
            window.scrollTo(0,0);
        }

    }
})();