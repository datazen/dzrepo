(function () {
    'use strict';

    angular
        .module('app')
        .factory('AdminPageAccessService', AdminPageAccessService);

    AdminPageAccessService.$inject = ['$http'];
    function AdminPageAccessService($http) {
        var service = {};

        service.GetAllAdminPageAccess = GetAllAdminPageAccess;
        service.GetAdminPageAccessById = GetAdminPageAccessById;
        service.GetAdminPageAccessByRoute = GetAdminPageAccessByRoute;
        service.GetAdminPageAccessByAccessLevel = GetAdminPageAccessByAccessLevel;
        service.UpdateAdminPageAccess = UpdateAdminPageAccess;

        return service;

        function GetAllAdminPageAccess(data) {
            return $http.post('/api/getAllAdminPageAccess', data).then(handleSuccess, handleError('Error getting all pages'));
        }

        function GetAdminPageAccessById(data) {
            return $http.post('/api/getAdminPageAccessById', data).then(handleSuccess, handleError('Error getting page access data by id'));
        }

        function GetAdminPageAccessByRoute(data) {  // slash is left off on purpose
            return $http.post('/api/getAdminPageAccessByRoute', data).then(handleSuccess, handleError('Error getting page access data by route'));
        }        

        function GetAdminPageAccessByAccessLevel(data) {
            return $http.post('/api/getAdminPageAccessByAccessLevel', data).then(handleSuccess, handleError('Error getting page access data by access level'));
        } 

        function UpdateAdminPageAccess(data) {
            return $http.post('/api/updateAdminPageAccess', data).then(handleSuccess, handleError('Error updating access for this page'));
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
