(function () {
    'use strict';

    angular
        .module('app')
        .controller('AdminPageAccessController', AdminPageAccessController);          

    AdminPageAccessController.$inject = ['AdminAccessLevelsService', 'AdminPageAccessService', 'AdminFlashService', '$rootScope', '$scope', '$location', '$timeout', '$uibModal', '$log', '$route'];
    function AdminPageAccessController(AdminAccessLevelsService, AdminPageAccessService, AdminFlashService, $rootScope, $scope, $location, $timeout, $uibModal, $log, $route) {
        var vm = this;

        vm.pages = [];
        vm.page = null;
    //    vm.accessLevels = [];
        vm.updateAdminPageAccess = updateAdminPageAccess;
        vm.showForm = showForm;

        vm.routes = $route.routes;

        initController();

        $scope.currentPage = 0;
        $scope.pageSize = ($rootScope.globals.config.PAGINATION_LENGTH) ? parseInt($rootScope.globals.config.PAGINATION_LENGTH) : 10;
        $scope.numberOfPages=function() {
            return Math.ceil($scope.vm.pages.length/$scope.pageSize);                
        }      

        $scope.go = function ( path ) {
          $location.path( path );
        };         

        function initController() {
            loadAllAdminPageAccess(); 
            loadAllAdminAccessLevels();          
        }

        function loadAllAdminAccessLevels() {
            AdminAccessLevelsService.GetAllAdminAccessLevels({ cID: $rootScope.globals.currentUser.cID })
                .then(function (levels) {
                    vm.accessLevels = levels.data;
                });
        }         

        function loadAllAdminPageAccess() {
            AdminPageAccessService.GetAllAdminPageAccess({ cID: $rootScope.globals.currentUser.cID, routes: vm.routes })
                .then(function (pages) {
                    vm.pages = pages.data;
                });
        } 

        function loadAdminPageAccess(id) {
            AdminPageAccessService.GetAdminPageAccessById({ cID: $rootScope.globals.currentUser.cID, id: id })
                .then(function (page) {
                    vm.page = page.data;
                });
        }               

        function updateAdminPageAccess() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;              
            AdminPageAccessService.UpdateAdminPageAccess(vm.page)
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        AdminFlashService.Success('Update page access successful', true);
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

            loadAdminPageAccess(id);

         //   $timeout(function(){  
                var modalInstance = $uibModal.open({
                    templateUrl: 'page-modal-form.html',
                    controller: modalInstanceCtrl,
                    scope: $scope,
                    animation: false,
                    resolve: {
                        accessForm: function () {
                            return $scope.pageAccess;
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

        function modalInstanceCtrl($scope, $uibModalInstance, accessForm) {
            $scope.form = {}
            $scope.submitForm = function () {
                if ($scope.form.pageAccess.$valid) {
                    console.log('Page access form is in scope');
                    $uibModalInstance.close('closed');

                    updateAdminPageAccess();
                    $timeout(function(){ loadAllAdminPageAccess(); }, 200);
                } else {
                    console.log('Page access form is NOT in scope');
                }
            };

            $scope.cancel = function () {
                $uibModalInstance.dismiss('cancel');
            };
        };        

    }

})();