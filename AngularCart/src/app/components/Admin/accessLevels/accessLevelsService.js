(function () {
    'use strict';

    angular
        .module('app')
        .factory('AdminAccessLevelsService', AdminAccessLevelsService);

    AdminAccessLevelsService.$inject = ['$http'];
    function AdminAccessLevelsService($http) {
        var service = {};

        service.GetAllAccessLevels = GetAllAccessLevels;
        service.GetAccessLevelById = GetAccessLevelById;
        service.UpdateAccessLevel = UpdateAccessLevel;

        return service;

        function GetAllAccessLevels() {
            return $http.get('/api/accessLevels').then(handleSuccess, handleError('Error getting all access levels'));
        }

        function GetAccessLevelById(id) {
            return $http.get('/api/getAccessLevelById/' + id).then(handleSuccess, handleError('Error getting access level by id'));
        }

        function UpdateAccessLevel(level) {
            return $http.post('/api/updateAccessLevel/' + level.id, level).then(handleSuccess, handleError('Error updating access level'));
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
