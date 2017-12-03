(function () {
    'use strict';

    angular
        .module('app')
        .controller('AdminLoginController', AdminLoginController);

    AdminLoginController.$inject = ['AdminLoginService', 'AdminFlashService', '$scope', '$location', '$timeout'];
    function AdminLoginController(AdminLoginService, AdminFlashService, $scope, $location, $timeout) {
        var vm = this;

        vm.login = login;

        (function initController() {
            // reset login status
            AdminLoginService.ClearCredentials();
        })();

        function login() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;             
            AdminLoginService.Login(vm.email, vm.password, function (response) {
                if (response.success == true) {
                    AdminLoginService.SetCredentials(vm.email, vm.password, response.data);
                    $location.path('/Admin/dashboard');
                } else {
                    var errMsg = (response.msg || 'An unknown API communication error has occurred');
                    AdminFlashService.Error(errMsg);
                    vm.dataLoading = false;
                }
                $timeout(function(){ $scope.startFade = true;
                    $timeout(function(){ 
                        $scope.hidethis = true; 
                        AdminFlashService.DeleteFlashMessage();
                    }, 200);
                }, 2000);                

            });
        };
    }

})();
