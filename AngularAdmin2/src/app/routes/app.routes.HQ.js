(function () {
    'use strict';

    angular
        .module('hqRoutes', ['ngRoute', 'ui.router'])
        .config(config);
    
    config.$inject = ['$routeProvider', '$stateProvider'];
    function config($routeProvider, $stateProvider) {
       /*
        * HQ Routes (state=private)
        */ 
        $stateProvider                    
            .state('HQ', {
                abstract: true,
                site: 'HQ',
                module: 'private'
            })
            .state('HQ.dashboard', {
                url: '/HQ/dashboard',
                site: 'HQ',               
                module: 'private'
            })
            .state('HQ.login', {
                url: '/HQ/login',
                site: 'HQ',                
                module: 'public'
            })
            .state('HQ.companies', {
                url: '/HQ/companies',
                site: 'HQ',                
                module: 'private'
            })
            .state('HQ.companies.addCompany', {
                url: '/HQ/companies/addCompany',
                site: 'HQ',                
                module: 'private'
            })
            .state('HQ.companies.editCompany', {
                url: '/HQ/companies/editCompany',
                site: 'HQ',                
                module: 'private'
            });                           
        
        $routeProvider 
            .when('/HQ', { 
                redirectTo: '/HQ/dashboard' 
            })
            .when('/HQ/dashboard', {
                controller: 'HQDashboardController',
                templateUrl: 'app/components/HQ/dashboard/dashboardView.php',
                controllerAs: 'vm'
            })
            .when('/HQ/login', {
                controller: 'HQLoginController',
                templateUrl: 'app/components/HQ/login/loginView.php',
                controllerAs: 'vm'
            })
            .when('/HQ/companies', {
                controller: 'HQCompaniesController',
                templateUrl: 'app/components/HQ/companies/companiesView.php',
                controllerAs: 'vm'
            })  
            .when('/HQ/addCompany', {
                controller: 'HQCompaniesController',
                templateUrl: 'app/components/HQ/companies/addCompanyView.php',
                controllerAs: 'vm'
            })     
            .when('/HQ/editCompany/:id', {
                controller: 'HQCompaniesController',
                templateUrl: 'app/components/HQ/companies/editCompanyView.php',
                controllerAs: 'vm'
            });
 
    }   

})();