(function () {
    'use strict';

    angular
        .module('app')
        .factory('StoreAccountService', StoreAccountService);

    StoreAccountService.$inject = ['$http'];
    function StoreAccountService($http) {
        var service = {};

        service.GetById = GetById;
        service.Create  = Create;
        service.Update  = Update;
        service.Delete  = Delete;

        return service; 

        function GetById(id) {
//            return $http({ method: 'GET', url: '/api/getById/' + id, cache: true }).then(handleSuccess, handleError('Error getting account by id'));
            return $http.get('/api/getStoreAccountById/' + id).then(handleSuccess, handleError('Error getting account by id'));
        }

        function Create(account) {
            return $http.post('/api/createStoreAccount', account).then(handleSuccess, handleError('Error creating account'));
        }

        function Update(account) {
            return $http.post('/api/updateStoreAccount/' + account.id, account).then(handleSuccess, handleError('Error updating account'));
        }

        function Delete(id) {
            return $http.get('/api/deleteStoreAccount/' + id).then(handleSuccess, handleError('Error deleting account'));
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
