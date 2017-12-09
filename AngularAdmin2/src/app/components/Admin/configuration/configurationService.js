(function () {
    'use strict';

    angular
        .module('app')
        .factory('AdminConfigurationService', AdminConfigurationService);

    AdminConfigurationService.$inject = ['$http'];
    function AdminConfigurationService($http) {
        var service = {};

        service.GetAllAdminConfigurations = GetAllAdminConfigurations;
        service.GetAllAdminConfigurationsByGroupId = GetAllAdminConfigurationsByGroupId;
        service.GetAdminConfigurationById = GetAdminConfigurationById;
        service.UpdateAdminConfiguration = UpdateAdminConfiguration;
        service.GetAllAdminConfigurationGroups = GetAllAdminConfigurationGroups;

        return service;

        function GetAllAdminConfigurations(data) {
            return $http.post('/api/getAllAdminConfigurations', data).then(handleSuccess, handleError('Error getting all configuration values'));
        }

        function GetAllAdminConfigurationsByGroupId(data) {
            return $http.post('/api/getAdminConfigurationsByGroupId', data).then(handleSuccess, handleError('Error getting all configuration values for groupId'));
        }

        function GetAdminConfigurationById(data) {
            return $http.post('/api/getAdminConfigurationById', data).then(handleSuccess, handleError('Error getting configuration value by id'));
        }

        function UpdateAdminConfiguration(data) {
            return $http.post('/api/updateAdminConfiguration', data).then(handleSuccess, handleError('Error updating configuration value'));
        }

        function GetAllAdminConfigurationGroups(data) {
            return $http.post('/api/getAllAdminConfigurationGroups', data).then(handleSuccess, handleError('Error getting configuration groups'));
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
