(function () {
    'use strict';

    angular
        .module('app')
        .factory('AdminUserService', AdminUserService);

    AdminUserService.$inject = ['$http'];
    function AdminUserService($http) {
        var service = {};

        service.GetAllAdminUsers = GetAllAdminUsers;
        service.GetAdminUserById = GetAdminUserById;
        service.GetAdminUserByEmail = GetAdminUserByEmail;
        service.CreateAdminUser = CreateAdminUser;
        service.UpdateAdminUser = UpdateAdminUser;
        service.DeleteAdminUser = DeleteAdminUser;

        return service;

        function GetAllAdminUsers(data) {
            return $http.post('/api/getAllAdminUsers', data).then(handleSuccess, handleError('Error getting all users'));
        }

        function GetAdminUserById(data) {
//            return $http({ method: 'GET', url: '/api/getById/' + id, cache: true }).then(handleSuccess, handleError('Error getting user by id'));
            return $http.post('/api/getAdminUserById', data).then(handleSuccess, handleError('Error getting user by id'));
        }

        function GetAdminUserByEmail(data) {
        //    return $http({ method: 'POST', url: '/api/getByUsername/' + username, data: data, cache: true }).then(handleSuccess, handleError('Error getting user by username'));
            return $http.post('/api/getAdminUserByEmail', data).then(handleSuccess, handleError('Error getting user by email'));
        }

        function CreateAdminUser(data) {
            return $http.post('/api/addAdminUser', data).then(handleSuccess, handleError('Error creating user'));
        }

        function UpdateAdminUser(data) {
            return $http.post('/api/updateAdminUser', data).then(handleSuccess, handleError('Error updating user'));
        }

        function DeleteAdminUser(data) {
            return $http.post('/api/deleteAdminUser', data).then(handleSuccess, handleError('Error deleting user'));
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
