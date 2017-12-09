(function () {
    'use strict';

    angular
        .module('app')
        .factory('AdminCompanyService', AdminCompanyService);

    AdminCompanyService.$inject = ['$http'];
    function AdminCompanyService($http) {
        var service = {};

        service.GetAdminCompanyById = GetAdminCompanyById;
        service.UpdateAdminCompany  = UpdateAdminCompany;

        return service;

        function GetAdminCompanyById(data) {
//            return $http({ method: 'POST', url: '/api/getById/' + id, data: data, cache: true }).then(handleSuccess, handleError('Error getting customer by id'));
            return $http.post('/api/getAdminCompanyById', data).then(handleSuccess, handleError('Error getting customer by id'));
        }    

        function UpdateAdminCompany(data) {
            return $http.post('/api/updateAdminCompany', data).then(handleSuccess, handleError('Error updating customer'));
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
