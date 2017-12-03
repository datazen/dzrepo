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
        vm.levels = [];
        vm.addUser = addUser;
        vm.updateUser = updateUser;
        vm.deleteUser = deleteUser;
        vm.editUserRecord = editUserRecord;

        vm.isEditUser = false;

        initController();

        function initController() {
            loadAllUsers();
            loadAllAccessLevels();

            if (window.location.href.indexOf('/Admin/editUser/') != -1) {
                var userId = window.location.href.substr(window.location.href.lastIndexOf('/') + 1);
                if (userId) loadUserRecord(userId);
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

        function loadCurrentUser() {
            AdminUserService.GetByUsername($rootScope.globals.currentUser.username)
                .then(function (user) {
                    vm.user = user.data;
                });
        }

        function loadAllUsers() {
            AdminUserService.GetAll()
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

        function loadAllAccessLevels() {
            AdminAccessLevelsService.GetAllAccessLevels()
                .then(function (levels) {
                    vm.levels = levels.data;
                });
        } 

        function loadUserRecord(id) {
            AdminUserService.GetById(id)
                .then(function (user) {
                    vm.userRecord = user.data;
                });
        }
       
        function editUserRecord(id) {
            $location.path('/Admin/editUser/' + id);                        
        }

        function deleteUser(id) {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;            
            AdminUserService.Delete(id)
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        AdminFlashService.Success('Delete user successful', true);
                        loadAllUsers();
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

        function addUser() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;            
            AdminUserService.Create(vm.userRecord)
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        $location.path('/users');
                        $timeout(function(){ AdminFlashService.Success('Add user successful', true); }, 500);
                    } else {
                        AdminFlashService.Error(response.msg);
                    }                     
                    vm.dataLoading = false;
                });
        }    

        function updateUser() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;              
            AdminUserService.Update(vm.userRecord)
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