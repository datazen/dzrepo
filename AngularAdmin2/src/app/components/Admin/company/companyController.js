(function () {
    'use strict';

    angular
        .module('app')
        .controller('AdminCompanyController', AdminCompanyController);   

    AdminCompanyController.$inject = ['AdminCompanyService', 'AdminFlashService', '$rootScope', '$scope', '$location', '$timeout'];
    function AdminCompanyController(AdminCompanyService, AdminFlashService, $rootScope, $scope, $location, $timeout) {
        var vm = this;

        vm.company = null;
        vm.updateCompany = updateCompany;

        initController();

        function initController() {
            window.scrollTo(0,0);
            loadCompany();
        }

        function loadCompany() {
            AdminCompanyService.GetById($rootScope.globals.currentUser.cID)
                .then(function (company) {
                    vm.company = company.data;
                });
        }

        function updateCompany() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;              
            AdminCompanyService.Update(vm.company)
                .then(function (response) {
                    window.scrollTo(0,0);
                    if (response.rpcStatus == 1) {
                        AdminFlashService.Success('Update company successful', true);
                    } else {
                        AdminFlashService.Error(response.msg);
                    }
                    vm.dataLoading = false;
                    $timeout(function(){ $scope.startFade = true;
                        $timeout(function(){ 
                            $scope.hidethis = true; 
                            AdminFlashService.DeleteFlashMessage();
                        }, 200);
                    }, 2000);                     
                });            
        }
        
    }

})();