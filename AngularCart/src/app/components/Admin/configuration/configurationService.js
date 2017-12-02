(function () {
    'use strict';

    angular
        .module('app')
        .factory('AdminConfigurationService', AdminConfigurationService);

    AdminConfigurationService.$inject = ['$http'];
    function AdminConfigurationService($http) {
        var service = {};

        service.GetAllConfigurationData = GetAllConfigurationData;
        service.GetAllConfigurations = GetAllConfigurations;
        service.GetConfigurationById = GetConfigurationById;
        service.UpdateConfiguration = UpdateConfiguration;
        service.GetConfigurationGroups = GetConfigurationGroups;

        return service;

        function GetAllConfigurationData() {
            return $http.get('/api/getAllConfigurationData').then(handleSuccess, handleError('Error getting all configuration values'));
        }

        function GetAllConfigurations(groupId) {
            return $http.get('/api/configurations/' + groupId).then(handleSuccess, handleError('Error getting all configuration values for groupId: ' + groupId));
        }

        function GetConfigurationById(id) {
            return $http.get('/api/getConfigurationById/' + id).then(handleSuccess, handleError('Error getting configuration value by id'));
        }

        function UpdateConfiguration(data) {
            return $http.post('/api/updateConfiguration/' + data.id, data).then(handleSuccess, handleError('Error updating configuration value'));
        }

        function GetConfigurationGroups() {
            return $http.get('/api/configurationGroups').then(handleSuccess, handleError('Error getting configuration groups'));
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
