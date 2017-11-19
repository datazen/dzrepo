(function () {
    'use strict';

    angular
        .module('app')
        .factory('UploadService', UploadService);

    UploadService.$inject = ['$http'];
    function UploadService($http) {
        var service = {};

        service.UpdateAvatar = UpdateAvatar;

        return service;

        function UpdateAvatar(user, fd) {

            return $http.post('/api/updateAvatar/' + user.id, fd, {
                       transformRequest: angular.identity,
                       headers: {'Content-Type': undefined}
                   }).then(handleSuccess, handleError('Error updating user'));


         //   return $http.post('/api/updateUser/' + user.id, user, config).then(handleSuccess, handleError('Error updating user'));
        }

        // private functions

        function handleSuccess(res) {
            return res.data;
        }

        function handleError(error) {
            return function () {
                return { success: false, message: error };
            };
        }        

    }

})();
