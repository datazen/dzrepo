(function () {
    'use strict';

    angular
        .module('app')
        .controller('SiteHomeController', SiteHomeController);

    SiteHomeController.$inject = ['$rootScope', '$location', '$scope', '$translate'];
    function SiteHomeController($rootScope, $location, $scope, $translate) {
        var vm = this;

        vm.language = 'en';
        vm.changeLanguage = changeLanguage;

        initController();

        function initController() {
        }

        function changeLanguage() {
            $translate.use(vm.language);
        };        

    }
})();