(function () {
    'use strict';

    angular
        .module('app')
        .factory('AdminUserService', AdminUserService);

    AdminUserService.$inject = ['$http'];
    function AdminUserService($http) {
        var service = {};

        service.GetAll = GetAll;
        service.GetById = GetById;
        service.GetByUsername = GetByUsername;
        service.Create = Create;
        service.Update = Update;
        service.Delete = Delete;

        return service;

        function GetAll() {
            return $http.get('/api/getAllAdminUsers').then(handleSuccess, handleError('Error getting all users'));
        }

        function GetById(id) {
//            return $http({ method: 'GET', url: '/api/getById/' + id, cache: true }).then(handleSuccess, handleError('Error getting user by id'));
            return $http.get('/api/getAdminUserById/' + id).then(handleSuccess, handleError('Error getting user by id'));
        }

        function GetByUsername(username) {
        //    return $http({ method: 'GET', url: '/api/getByUsername/' + username, cache: true }).then(handleSuccess, handleError('Error getting user by username'));
            return $http.get('/api/getAdminUserByUsername/' + username).then(handleSuccess, handleError('Error getting user by username'));
        }

        function Create(user) {
            return $http.post('/api/addAdminUser', user).then(handleSuccess, handleError('Error creating user'));
        }

        function Update(user) {
            return $http.post('/api/updateAdminUser/' + user.id, user).then(handleSuccess, handleError('Error updating user'));
        }

        function Delete(id) {
            return $http.get('/api/deleteAdminUser/' + id).then(handleSuccess, handleError('Error deleting user'));
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
