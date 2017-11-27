(function () {
    'use strict';

    angular
        .module('app')
        .controller('LoginController', LoginController);

    LoginController.$inject = ['$location', 'AuthenticationService', 'FlashService', '$scope', '$timeout'];
    function LoginController($location, AuthenticationService, FlashService, $scope, $timeout) {
        var vm = this;

        vm.login = login;

        (function initController() {
            // reset login status
            AuthenticationService.ClearCredentials();
        })();

        function login() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;             
            AuthenticationService.Login(vm.username, vm.password, function (response) {
                if (response.success == true) {
                    AuthenticationService.SetCredentials(vm.username, vm.password, response.data);
                    $location.path('/');
                } else {
                    FlashService.Error(response.msg);
                    vm.dataLoading = false;
                }
                $timeout(function(){ $scope.startFade = true;
                    $timeout(function(){ 
                        $scope.hidethis = true; 
                        FlashService.DeleteFlashMessage();
                    }, 200);
                }, 2000);                

            });
        };
    }

})();
