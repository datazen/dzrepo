(function () {
    'use strict';

    angular
        .module('app')
        .controller('AdminAccessLevelsController', AdminAccessLevelsController);

    AdminAccessLevelsController.$inject = ['AdminAccessLevelsService', 'AdminFlashService', '$rootScope', '$scope', '$location', '$timeout', '$uibModal', '$log'];
    function AdminAccessLevelsController(AdminAccessLevelsService, AdminFlashService, $rootScope, $scope, $location, $timeout, $uibModal, $log) {
        var vm = this;

        vm.accessLevels = [];
        vm.accessLevel = null;
        vm.updateAccessLevel = updateAccessLevel;
        vm.showForm = showForm;

        initController();

        $scope.go = function ( path ) {
          $location.path( path );
        };         

        function initController() {
            loadAllAccessLevels();           
        }

        function loadAllAccessLevels() {
            AdminAccessLevelsService.GetAllAccessLevels()
                .then(function (levels) {
                    vm.accessLevels = levels.data;
                });
        } 

        function loadAccessLevel(id) {
            AdminAccessLevelsService.GetAccessLevelById(id)
                .then(function (level) {
                    vm.accessLevel = level.data;
                });
        }               

        function updateAccessLevel() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;              
            AdminAccessLevelsService.UpdateAccessLevel(vm.accessLevel)
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        AdminFlashService.Success('Update access level successful', true);
                       // $location.path('/editEmployee');
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

            loadAccessLevel(id);

            var modalInstance = $uibModal.open({
                templateUrl: 'access-modal-form.html',
                controller: modalInstanceCtrl,
                scope: $scope,
                animation: false,
                resolve: {
                    accessForm: function () {
                        return $scope.accessForm;
                    }
                }
            });

            modalInstance.result.then(function (selectedItem) {
                $scope.selected = selectedItem;
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        }

        function modalInstanceCtrl($scope, $uibModalInstance, accessForm) {
            $scope.form = {}
            $scope.submitForm = function () {
                if ($scope.form.accessForm.$valid) {
                    console.log('Access levels form is in scope');
                    $uibModalInstance.close('closed');

                    updateAccessLevel();
                    $timeout(function(){ loadAllAccessLevels(); }, 200);
                } else {
                    console.log('Access levels form is NOT in scope');
                }
            };

            $scope.cancel = function () {
                $uibModalInstance.dismiss('cancel');
            };
        };        

    }

})();