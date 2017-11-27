(function () {
    'use strict';

    angular
        .module('app')
        .controller('PageAccessController', PageAccessController)
        .filter('startFrom', function() {
                    return function(input, start) {
                        start = +start; //parse to int
                        return input.slice(start);
                    }
                });           

    PageAccessController.$inject = ['AccessService', '$rootScope', 'FlashService', '$scope', '$location', '$timeout', '$uibModal', '$log', '$route'];
    function PageAccessController(AccessService, $rootScope, FlashService, $scope, $location, $timeout, $uibModal, $log, $route) {
        var vm = this;

        vm.pages = [];
        vm.page = null;
        vm.accessLevels = [];
        vm.updatePage = updatePage;
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
            loadAllPages(); 
            loadAllAccessLevels();          
        }

        function loadAllAccessLevels() {
            AccessService.GetAllAccessLevels()
                .then(function (levels) {
                    vm.accessLevels = levels.data;
                });
        }         

        function loadAllPages() {
            AccessService.GetAllPages(vm.routes)
                .then(function (pages) {
                    vm.pages = pages.data;
                });
        } 

        function loadPage(id) {
            AccessService.GetPageById(id)
                .then(function (page) {
                    vm.page = page.data;
                });
        }               

        function updatePage() {
            vm.dataLoading = true;
            $scope.hidethis = false;
            $scope.startFade = false;              
            AccessService.UpdatePage(vm.page)
                .then(function (response) {
                    if (response.rpcStatus == 1) {
                        FlashService.Success('Update page access successful', true);
                    } else {
                        FlashService.Error(response.msg);
                        vm.dataLoading = false;
                    }
                    $timeout(function(){ $scope.startFade = true;
                        $timeout(function(){ 
                            $scope.hidethis = true; 
                            FlashService.DeleteFlashMessage();
                        }, 200);
                    }, 2000);                     
                });
        }

        function showForm(id) {

            $scope.message = "Show form button clicked";
            console.log($scope.message);

            loadPage(id);

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

                    updatePage();
                    $timeout(function(){ loadAllPages(); }, 200);
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