(function () {
    'use strict';

    angular
        .module('app')
        .controller('StoreLoginController', StoreLoginController);

    StoreLoginController.$inject = ['StoreLoginService', 'StoreFlashService', '$scope', '$location', '$timeout'];
    function StoreLoginController(StoreLoginService, StoreFlashService, $scope, $location, $timeout) {
        var vm = this;

        vm.login = login;

        (function initController() {
            // reset login status
            StoreLoginService.ClearCredentials();
        })();

        function login() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;             
            StoreLoginService.Login(vm.username, vm.password, function (response) {
                if (response.success == true) {
                    StoreLoginService.SetCredentials(vm.username, vm.password, response.data);
                    $location.path('/');
                } else {
                    StoreFlashService.Error(response.msg);
                    vm.dataLoading = false;
                }
                $timeout(function(){ $scope.startFade = true;
                    $timeout(function(){ 
                        $scope.hidethis = true; 
                        StoreFlashService.DeleteFlashMessage();
                    }, 200);
                }, 2000);                

            });
        };
    }

})();
