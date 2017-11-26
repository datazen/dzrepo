(function () {
    'use strict';

    angular
        .module('app')
        .controller('ConfigurationController', ConfigurationController)
        .filter('startFrom', function() {
                    return function(input, start) {
                        start = +start; //parse to int
                        return input.slice(start);
                    }
                });           

    ConfigurationController.$inject = ['ConfigurationService', '$rootScope', 'FlashService', '$scope', '$location', '$timeout', '$uibModal', '$log'];
    function ConfigurationController(ConfigurationService, $rootScope, FlashService, $scope, $location, $timeout, $uibModal, $log) {
        var vm = this;

        vm.configurations = [];
        vm.configuration = null;
        vm.configurationGroups = [];
        vm.updateConfiguration = updateConfiguration;
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

            if (window.location.href.indexOf('configuration/') != -1) {
                var groupId = window.location.href.substr(window.location.href.lastIndexOf('/') + 1);
                if (groupId) loadAllConfigurations(groupId);
            }  

        }

        function loadAllConfigurations(groupId) {
            ConfigurationService.GetAllConfigurations(groupId)
                .then(function (configurations) {
                    vm.configurations = configurations.data;
                });
        }    

        function loadConfigurationGroups() {
            ConfigurationService.GetConfigurationGroups()
                .then(function (groups) {
                    vm.configurationGroups = groups.data;
//alert(print_r(groups, true));                    
                });
        } 

        function loadConfiguration(id) {
            ConfigurationService.GetConfigurationById(id)
                .then(function (configuration) {
                    vm.configuration = configuration;
                });
        } 

        function updateConfiguration() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;              
            ConfigurationService.Update(vm.configurations)
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        FlashService.Success('Update configuration successful', true);
                    } else {
                        FlashService.Error(response.msg);
                        vm.dataLoading = false;
                    }
                    $timeout(function(){ $scope.startFade = true;
                        $timeout(function(){ $scope.hidethis = true; }, 200);
                    }, 2000);                     
                });
        }        

        function showForm(id) {

            $scope.message = "Show form button clicked";
            console.log($scope.message);

            loadConfiguration(id);

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
                if ($scope.form.configuration.$valid) {
                    console.log('Configuration form is in scope');
                    $uibModalInstance.close('closed');

                    updateConfiguration();
                    loadAllConfigurations();
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