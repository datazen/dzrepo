(function () {
    'use strict';

    angular
        .module('siteRoutes', ['ngRoute', 'ui.router'])
        .config(config);
    

    config.$inject = ['$routeProvider', '$stateProvider'];
    function config($routeProvider, $stateProvider) {
       /*
        * Frontend Routes
        */
        $stateProvider
            .state('public', {
                abstract: true,
                site: 'Site',
                module: 'public'
            })
            .state('public.home', {
                url: '/home',
                site: 'Site',            
                module: 'public'
            })
            .state('public.home.thankyou', {
                url: '/home/thankyou',
                site: 'Site',            
                module: 'public'
            });

        $routeProvider  
            .when('/', {
                redirectTo: '/home',
            })
            .when('/home', {
                controller: 'SiteHomeController',
                templateUrl: 'app/components/Site/home/homeView.php',
                controllerAs: 'vm'
            })
            .when('/home/thankyou', {
                controller: 'SiteHomeController',
                templateUrl: 'app/components/Site/home/thankyouView.php',
                controllerAs: 'vm'
            });
    }   

})();