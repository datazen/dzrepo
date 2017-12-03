(function () {
    'use strict';

    angular
        .module('app')
        .factory('StoreCustomersService', StoreCustomersService);

    StoreCustomersService.$inject = ['$http'];
    function StoreCustomersService($http) {
        var service = {};

        service.GetAll  = GetAll;
        service.GetById = GetById;
        service.Create  = Create;
        service.Update  = Update;
        service.Delete  = Delete;

        return service;

        function GetAll() {
            return $http.get('/api/customers').then(handleSuccess, handleError('Error getting all customers'));
        }

        function GetById(id) {
//            return $http({ method: 'GET', url: '/api/getById/' + id, cache: true }).then(handleSuccess, handleError('Error getting customer by id'));
            return $http.get('/api/getCustomerById/' + id).then(handleSuccess, handleError('Error getting customer by id'));
        }

        function Create(customer) {
            return $http.post('/api/createCustomer', customer).then(handleSuccess, handleError('Error creating customer'));
        }

        function Update(customer) {
            return $http.post('/api/updateCustomer/' + customer.id, customer).then(handleSuccess, handleError('Error updating customer'));
        }

        function Delete(id) {
            return $http.get('/api/deleteCustomer/' + id).then(handleSuccess, handleError('Error deleting customer'));
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
