(function () {
    'use strict';

    angular
        .module('app')
        .controller('ProfileController', ProfileController)
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
        }])
        .directive('passwordVerify', passwordVerify);
        
    ProfileController.$inject = ['UserService', '$location', '$scope', '$rootScope', 'FlashService', 'UploadService', '$timeout'];
    function ProfileController(UserService, $location, $scope, $rootScope, FlashService, UploadService, $timeout) {
        var vm = this;

        vm.user = null;
        vm.updateProfile = updateProfile;
        vm.updateAvatar = updateAvatar;

        initController();

        function initController() {
            loadCurrentUser();
        }

        function loadCurrentUser() {
            UserService.GetByUsername($rootScope.globals.currentUser.username)
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
            UploadService.UpdateAvatar(vm.user, fd)
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        window.scrollTo(0,0);
                        vm.user.avatar = file.name + '?' + new Date().getTime();                        
                        $rootScope.globals.currentUser.avatar = file.name + '?' + new Date().getTime();                        

                        FlashService.Success('Update successful', true);
                        vm.dataLoading2 = false;   
                    } else {
                        window.scrollTo(0,0);                       
                        FlashService.Error(response.msg);
                        vm.dataLoading2 = false;
                    }
                    $timeout(function(){ $scope.startFade = true;
                        $timeout(function(){ $scope.hidethis = true; }, 200);
                    }, 2000);                    
                });
        } 

        function updateProfile() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;            
            UserService.Update(vm.user)
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        window.scrollTo(0,0);                       
                        FlashService.Success('Update successful', true);
                        vm.dataLoading = false;
                    } else {
                        window.scrollTo(0,0);
                        FlashService.Error(response.msg);
                        vm.dataLoading = false;
                    }     
                    $timeout(function(){ $scope.startFade = true;
                        $timeout(function(){ $scope.hidethis = true; }, 200);
                    }, 2000);                                    
                });
        }        
    }

    function passwordVerify() {
        return {
          restrict: 'A', // only activate on element attribute
          require: '?ngModel', // get a hold of NgModelController
          link: function(scope, elem, attrs, ngModel) {
            if (!ngModel) return; // do nothing if no ng-model

            // watch own value and re-validate on change
            scope.$watch(attrs.ngModel, function() {
              validate();
            });

            // observe the other value and re-validate on change
            attrs.$observe('passwordVerify', function(val) {
              validate();
            });

            var validate = function() {
              // values
              var val1 = ngModel.$viewValue;
              var val2 = attrs.passwordVerify;

              // set validity
              ngModel.$setValidity('passwordVerify', val1 === val2);
            };
          }
        }
    }    

})();