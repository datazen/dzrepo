(function () {
    'use strict';

    angular
        .module('app')
        .controller('AdminProfileController', AdminProfileController)
        .directive('fileModel', ['$parse', function ($parse) {
            return {
                restrict: 'A',
                link: function(scope, element, attrs) {
                    var model = $parse(attrs.fileModel);
                    var modelSetter = model.assign;
                    element.bind('change', function(){
                        scope.$apply(function(){
                            modelSetter(scope, element[0].files[0]);
                        });
                    });
                }
            };
        }]);
        
    AdminProfileController.$inject = ['AdminUserService', 'AdminFlashService', 'AdminUploadService', '$rootScope', '$scope', '$location', '$timeout'];
    function AdminProfileController(AdminUserService, AdminFlashService, AdminUploadService, $rootScope, $scope, $location, $timeout) {
        var vm = this;

        vm.user = null;
        vm.updateProfile = updateProfile;
        vm.updateAvatar = updateAvatar;

        initController();

        function initController() {
            window.scrollTo(0,0);
            loadCurrentUser();
        }

        function loadCurrentUser() {
            AdminUserService.GetAdminUserByEmail({ cID: $rootScope.globals.currentUser.cID, email: $rootScope.globals.currentUser.email })
                .then(function (user) {
                    vm.user = user.data;
                });
        }

        function updateAvatar () {
            var file = $scope.myFile;
            var fd = new FormData();
            fd.append('file', file);
            vm.dataLoading2 = true;
            $scope.hidethis = false;
            $scope.startFade = false;
            AdminUploadService.UpdateAvatar(vm.user, fd)
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        window.scrollTo(0,0);
                        vm.user.avatar = file.name + '?' + new Date().getTime();                        
                        $rootScope.globals.currentUser.avatar = file.name + '?' + new Date().getTime();                        

                        AdminFlashService.Success('Update successful', true);
                        vm.dataLoading2 = false;   
                    } else {
                        window.scrollTo(0,0);                       
                        AdminFlashService.Error(response.msg);
                        vm.dataLoading2 = false;
                    }
                    $timeout(function(){ $scope.startFade = true;
                        $timeout(function(){ 
                            $scope.hidethis = true; 
                            AdminFlashService.DeleteFlashMessage();
                        }, 200);
                    }, 2000);                    
                });
        } 

        function updateProfile() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;            
            AdminUserService.UpdateAdminUser(vm.user)
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        window.scrollTo(0,0);                       
                        AdminFlashService.Success('Update successful', true);
                        vm.dataLoading = false;
                    } else {
                        window.scrollTo(0,0);
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
    } 

})();