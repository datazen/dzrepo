(function () {
    'use strict';

    angular
        .module('app')
        .controller('HeaderCtrl', HeaderCtrl); 

    HeaderCtrl.$inject = ['UserService', '$rootScope', '$scope'];
    function HeaderCtrl(UserService, $rootScope, $scope) {
        var vm = this;

        $scope.thisUser = null;

        initController();

        function initController() {
            loadCurrentUser();
        }

        function loadCurrentUser() {
            UserService.GetByUsername($rootScope.globals.currentUser.username)
                .then(function (user) {
                    $scope.thisUser = user;
                });
        }

    }

})();