(function () {
    'use strict';

    angular
        .module('app')
        .factory('AdminAccessLevelsService', AdminAccessLevelsService);

    AdminAccessLevelsService.$inject = ['$http'];
    function AdminAccessLevelsService($http) {
        var service = {};

        service.GetAllAdminAccessLevels = GetAllAdminAccessLevels;
        service.GetAdminAccessLevelById = GetAdminAccessLevelById;
        service.UpdateAdminAccessLevel = UpdateAdminAccessLevel;

        return service;

        function GetAllAdminAccessLevels(data) {
            return $http.post('/api/getAllAdminAccessLevels', data).then(handleSuccess, handleError('Error getting all access levels'));
        }

        function GetAdminAccessLevelById(data) {
            return $http.post('/api/getAdminAccessLevelById', data).then(handleSuccess, handleError('Error getting access level by id'));
        }

        function UpdateAdminAccessLevel(data) {
            return $http.post('/api/updateAdminAccessLevel', data).then(handleSuccess, handleError('Error updating access level'));
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
