(function () {
    'use strict';

    angular
        .module('app')
        .factory('AdminCompanyService', AdminCompanyService);

    AdminCompanyService.$inject = ['$http'];
    function AdminCompanyService($http) {
        var service = {};

        service.GetById    = GetById;
        service.Update     = Update;

        return service;

        function GetById(id) {
//            return $http({ method: 'GET', url: '/api/getById/' + id, cache: true }).then(handleSuccess, handleError('Error getting customer by id'));
            return $http.get('/api/getAdminCompanyById/' + id).then(handleSuccess, handleError('Error getting customer by id'));
        }    

        function Update(company) {
            return $http.post('/api/updateAdminCompany/' + company.id, company).then(handleSuccess, handleError('Error updating customer'));
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
