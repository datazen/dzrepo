(function () {
    'use strict';

    angular
        .module('app')
        .controller('AdminConfigurationController', AdminConfigurationController)
        .filter('startFrom', function() {
                    return function(input, start) {
                        start = +start; //parse to int
                        return input.slice(start);
                    }
                });           

    AdminConfigurationController.$inject = ['AdminConfigurationService', 'AdminFlashService', '$rootScope', '$scope', '$location', '$timeout', '$uibModal', '$log'];
    function AdminConfigurationController(AdminConfigurationService, AdminFlashService, $rootScope, $scope, $location, $timeout, $uibModal, $log) {
        var vm = this;

        vm.configurations = [];
        vm.configuration = null;
        vm.configurationGroups = [];
        vm.updateAdminConfiguration = updateAdminConfiguration;
        vm.showForm = showForm;

        initController();

        $scope.currentPage = 0;
        $scope.pageSize = ($rootScope.globals.config.PAGINATION_LENGTH) ? $rootScope.globals.config.PAGINATION_LENGTH : 10;
        $scope.numberOfPages=function() {
            return Math.ceil($scope.vm.configurations.length/$scope.pageSize);                
        }      

        $scope.go = function ( path ) {
          $location.path( path );
        };         

        function initController() {

            if (window.location.href.indexOf('/Admin/configuration') != -1) {
                var groupId = ($rootScope.globals.adminConfigGroupToShow) ? $rootScope.globals.adminConfigGroupToShow : 1;
                //delete($rootScope.globals.adminConfigRecordToEdit);
                if (groupId) loadAllAdminConfigurations(groupId);
            }  

        }

        function loadAllAdminConfigurations(groupId) {
            AdminConfigurationService.GetAllAdminConfigurationsByGroupId({ cID: $rootScope.globals.currentUser.cID, groupId: groupId })
                .then(function (configurations) {
                    vm.configurations = configurations.data;
                });
        }    

        function loadAdminConfigurationGroups() {
            AdminConfigurationService.GetAllAdminConfigurationGroups({ cID: $rootScope.globals.currentUser.cID })
                .then(function (groups) {
                    vm.configurationGroups = groups.data;
                });
        } 

        function loadAdminConfigurationById(id) {
            AdminConfigurationService.GetAdminConfigurationById({ cID: $rootScope.globals.currentUser.cID, id: id})
                .then(function (configuration) {
                    vm.configuration = configuration.data;
                });
        } 

        function updateAdminConfiguration() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;              
            AdminConfigurationService.UpdateAdminConfiguration(vm.configuration)
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        AdminFlashService.Success('Update configuration successful', true);
                    } else {
                        AdminFlashService.Error(response.msg);
                        vm.dataLoading = false;
                    }
                    $timeout(function(){ $scope.startFade = true;
                        $timeout(function(){ 
                            $scope.hidethis = true; 
                            AdminFlashService.DeleteFlashMessage();                            
                        }, 200);
                    }, 2000);                     
                });
        }        

        function showForm(id) {
            $scope.message = "Show form button clicked";
            console.log($scope.message);

            loadAdminConfigurationById(id);

         //   $timeout(function(){  
                var modalInstance = $uibModal.open({
                    templateUrl: 'configuration-modal-form.html',
                    controller: modalInstanceCtrl,
                    scope: $scope,
                    animation: false,
                    resolve: {
                        configurationForm: function () {
                            return $scope.configurationForm;
                        }
                    }
                });
          //  }, 500);

            modalInstance.result.then(function (selectedItem) {
                $scope.selected = selectedItem;
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        }

        function modalInstanceCtrl($scope, $uibModalInstance, configurationForm) {
            $scope.form = {}
            $scope.submitForm = function () {
                if ($scope.form.configurationForm.$valid) {
                    console.log('Configuration form is in scope');
                    $uibModalInstance.close('closed');

                    updateAdminConfiguration();
                    $timeout(function(){ initController(); }, 200);
                } else {
                    console.log('Configuration form is NOT in scope');
                }
            };

            $scope.cancel = function () {
                $uibModalInstance.dismiss('cancel');
            };
        };        

    }

})();