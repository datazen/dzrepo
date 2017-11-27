(function () {
    'use strict';

    angular
        .module('app')
        .controller('UsersController', UsersController)
        .directive('stringToNumber', function() {
          return {
            require: 'ngModel',
            link: function(scope, element, attrs, ngModel) {
              ngModel.$parsers.push(function(value) {
                return '' + value;
              });
              ngModel.$formatters.push(function(value) {
                return parseFloat(value);
              });
            }
          };
        }).directive('ngConfirmClick', [
            function(){
                return {
                    link: function (scope, element, attr) {
                        var msg = attr.ngConfirmClick || "Are you sure?";
                        var clickAction = attr.confirmedClick;
                        element.bind('click',function (event) {
                            if ( window.confirm(msg) ) {
                                scope.$eval(clickAction)
                            }
                        });
                    }
                };
        }])
        .filter('startFrom', function() {
            return function(input, start) {
                start = +start; //parse to int
                return input.slice(start);
            }
        });   

    UsersController.$inject = ['UserService', 'AccessService', '$rootScope', 'FlashService', '$scope', '$location', '$timeout'];
    function UsersController(UserService, AccessService, $rootScope, FlashService, $scope, $location, $timeout) {
        var vm = this;

        vm.user = null;
        vm.userRecord = null;
        vm.allUsers = [];
        vm.levels = [];
        vm.addUser = addUser;
        vm.updateUser = updateUser;
        vm.deleteUser = deleteUser;
        vm.editUserRecord = editUserRecord;

        initController();

        function initController() {
        //    loadCurrentUser();
            loadAllUsers();
            loadAllAccessLevels();

            if (window.location.href.indexOf('editUser/') != -1) {
                var userId = window.location.href.substr(window.location.href.lastIndexOf('/') + 1);
                if (userId) loadUserRecord(userId);
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
            UserService.GetByUsername($rootScope.globals.currentUser.username)
                .then(function (user) {
                    vm.user = user.data;
                });
        }

        function loadAllUsers() {
            UserService.GetAll()
                .then(function (users) {
                    vm.allUsers = users.data;
                });

            // fade out the alert if there is one
            $timeout(function(){ $scope.startFade = true;
                $timeout(function(){ 
                    $scope.hidethis = true; 
                    FlashService.DeleteFlashMessage();
                }, 200);
            }, 2000);                  
        }

        function loadAllAccessLevels() {
            AccessService.GetAllAccessLevels()
                .then(function (levels) {
                    vm.levels = levels.data;
                });
        } 

        function loadUserRecord(id) {
            UserService.GetById(id)
                .then(function (user) {
                    vm.userRecord = user.data;
                });
        }
       
        function editUserRecord(id) {
            $location.path('/editUser/' + id);                        
        }

        function deleteUser(id) {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;            
            UserService.Delete(id)
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        FlashService.Success('Delete user successful', true);
                        loadAllUsers();
                    } else {
                        FlashService.Error(response.msg);
                    }
                    vm.dataLoading = false;
                    $timeout(function(){ $scope.startFade = true;
                        $timeout(function(){ 
                            $scope.hidethis = true; 
                            FlashService.DeleteFlashMessage();
                        }, 200);
                    }, 2000);                      
                });        
        }        

        function addUser() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;            
            UserService.Create(vm.userRecord)
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        $location.path('/users');
                        $timeout(function(){ FlashService.Success('Add user successful', true); }, 500);
                    } else {
                        FlashService.Error(response.msg);
                    }                     
                    vm.dataLoading = false;
                });
        }    

        function updateUser() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;              
            UserService.Update(vm.userRecord)
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        FlashService.Success('Update user successful', true);
                    } else {
                        FlashService.Error(response.msg);
                    }
                    vm.dataLoading = false;
                    $timeout(function(){ $scope.startFade = true;
                        $timeout(function(){ 
                            $scope.hidethis = true; 
                            FlashService.DeleteFlashMessage();
                        }, 200);
                    }, 2000);                     
                });
        }

    }

})();