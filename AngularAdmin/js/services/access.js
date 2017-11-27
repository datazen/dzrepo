(function () {
    'use strict';

    angular
        .module('app')
        .factory('AccessService', AccessService);

    AccessService.$inject = ['$http'];
    function AccessService($http) {
        var service = {};

        service.GetAllAccessLevels = GetAllAccessLevels;
        service.GetAccessLevelById = GetAccessLevelById;
        service.UpdateAccessLevel = UpdateAccessLevel;
        service.GetAllPages = GetAllPages;
        service.GetPageById = GetPageById;
        service.GetPageByRoute = GetPageByRoute;
        service.GetPagesByAccessLevel = GetPagesByAccessLevel;
        service.UpdatePage = UpdatePage;

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

        function GetAllPages(routes) {
            return $http.post('/api/getAllPages', routes).then(handleSuccess, handleError('Error getting all pages'));
        }

        function GetPageById(id) {
            return $http.get('/api/getPageById/' + id).then(handleSuccess, handleError('Error getting page access data by id'));
        }

        function GetPageByRoute(route) {  // slash is left off on purpose
            return $http.get('/api/getPageByRoute' + route).then(handleSuccess, handleError('Error getting page access data by route'));
        }        

        function GetPagesByAccessLevel(level) {
            return $http.get('/api/getPagesByAccessLevel/' + level).then(handleSuccess, handleError('Error getting page access data by route'));
        } 

        function UpdatePage(page) {
            return $http.post('/api/updatePage/' + page.id, page).then(handleSuccess, handleError('Error updating access for this page'));
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
