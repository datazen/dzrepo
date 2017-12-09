(function () {
    'use strict';

    angular
        .module('app')
        .controller('AdminUsersController', AdminUsersController);  

    AdminUsersController.$inject = ['AdminUserService', 'AdminAccessLevelsService', 'AdminFlashService', '$rootScope', '$scope', '$location', '$timeout'];
    function AdminUsersController(AdminUserService, AdminAccessLevelsService, AdminFlashService, $rootScope, $scope, $location, $timeout) {
        var vm = this;

        vm.user = null;
        vm.userRecord = null;
        vm.allUsers = [];
        vm.addAdminUser = addAdminUser;
        vm.updateAdminUser = updateAdminUser;
        vm.deleteAdminUser = deleteAdminUser;
        vm.editAdminUserRecord = editAdminUserRecord;
        vm.levels = [];

        vm.isEditUser = false;

        initController();

        function initController() {
            loadAllAdminUsers();
            loadAllAdminAccessLevels();

            if (window.location.href.indexOf('/Admin/editUser') != -1) {
                if($rootScope.globals.adminUserRecordToEdit == undefined) $location.path('/Admin/users');
                var userId = $rootScope.globals.adminUserRecordToEdit;
              //  delete($rootScope.globals.adminUserRecordToEdit);
                if (userId) loadAdminUserRecord(userId);
                vm.isEditUser = true;
            }            
        }

        $scope.currentPage = 0;
        $scope.pageSize = ($rootScope.globals.config.PAGINATION_LENGTH) ? parseInt($rootScope.globals.config.PAGINATION_LENGTH) : 10;
        $scope.numberOfPages=function() {
            return Math.ceil($scope.vm.allUsers.length/$scope.pageSize);                
        }    

        $scope.go = function ( path ) {
          $location.path( path );
        }; 

        function loadCurrentAdminUser() {
            AdminUserService.GetAdminUserByEmail({ cID: $rootScope.globals.currentUser.cID, email: $rootScope.globals.currentUser.email })
                .then(function (user) {
                    vm.user = user.data;
                });
        }

        function loadAllAdminUsers() {
            AdminUserService.GetAllAdminUsers({ cID: $rootScope.globals.currentUser.cID })
                .then(function (users) {
                    vm.allUsers = users.data;
                });

            // fade out the alert if there is one
            $timeout(function(){ $scope.startFade = true;
                $timeout(function(){ 
                    $scope.hidethis = true; 
                    AdminFlashService.DeleteFlashMessage();
                }, 200);
            }, 2000);                  
        }

        function loadAllAdminAccessLevels() {
            AdminAccessLevelsService.GetAllAdminAccessLevels({ cID: $rootScope.globals.currentUser.cID })
                .then(function (levels) {
                    vm.levels = levels.data;
                });
        } 

        function loadAdminUserRecord(id) {
            AdminUserService.GetAdminUserById({ cID: $rootScope.globals.currentUser.cID, id: id })
                .then(function (user) {
                    vm.userRecord = user.data;
                });
        }
       
        function editAdminUserRecord(id) {
            $rootScope.globals.adminUserRecordToEdit = id;  
            $location.path('/Admin/editUser');
        }

        function deleteAdminUser(id) {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;            
            AdminUserService.DeleteAdminUser({ cID: $rootScope.globals.currentUser.cID, id: id })
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        AdminFlashService.Success('Delete user successful', true);
                        loadAllAdminUsers();
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

        function addAdminUser() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;            
            AdminUserService.CreateAdminUser({ cID: $rootScope.globals.currentUser.cID, data: vm.userRecord })
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        $location.path('/Admin/users');
                        $timeout(function(){ AdminFlashService.Success('Add user successful', true); }, 500);
                    } else {
                        AdminFlashService.Error(response.msg);
                    }                     
                    vm.dataLoading = false;
                });
        }    

        function updateAdminUser() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;   
            AdminUserService.UpdateAdminUser(vm.userRecord)
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        AdminFlashService.Success('Update user successful', true);
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