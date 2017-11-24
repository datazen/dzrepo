(function () {
    'use strict';

    angular
        .module('app')
        .factory('ConfigurationService', ConfigurationService);

    ConfigurationService.$inject = ['$http'];
    function ConfigurationService($http) {
        var service = {};

        service.GetAllConfigurations = GetAllConfigurations;
        service.GetConfigurationById = GetConfigurationById;
        service.UpdateConfiguration = UpdateConfiguration;
        service.GetConfigurationGroups = GetConfigurationGroups;

        return service;

        function GetAllConfigurations() {
            return $http.get('/api/configurations').then(handleSuccess, handleError('Error getting all configuration values'));
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
