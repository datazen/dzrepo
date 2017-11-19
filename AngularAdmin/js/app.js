(function () {
    'use strict';

    angular
        .module('app', ['ngRoute', 'ngCookies', 'ngAnimate', 'ngSanitize', 'ngTouch', 'ngMessages', 'ui.bootstrap', 'ui.bootstrap'])
        .config(config)
        .run(run);

    config.$inject = ['$routeProvider', '$locationProvider'];
    function config($routeProvider, $locationProvider) {
        $routeProvider
            .when('/', {
                redirectTo: '/dashboard'
            })

            .when('/dashboard', {
                controller: 'HomeController',
                templateUrl: 'pages/dashboard.php',
                controllerAs: 'vm'
            })

            .when('/login', {
                controller: 'LoginController',
                templateUrl: 'pages/login.php',
                controllerAs: 'vm'
            })

            .when('/register', {
                controller: 'RegisterController',
                templateUrl: 'pages/register.php',
                controllerAs: 'vm'
            }) 

            .when('/profile', {
                controller: 'ProfileController',
                templateUrl: 'pages/profile.php',
                controllerAs: 'vm'
            }) 

            .when('/users', {
                controller: 'UsersController',
                templateUrl: 'pages/users.php',
                controllerAs: 'vm'
            })  

            .when('/addUser', {
                controller: 'UsersController',
                templateUrl: 'pages/addUser.php',
                controllerAs: 'vm'
            })     

            .when('/editUser/', {
                controller: 'UsersController',
                templateUrl: 'pages/editUser.php',
                controllerAs: 'vm'
            }) 


            .when('/page-1', {
                controller: 'HomeController',
                templateUrl: 'pages/page-1.php',
                controllerAs: 'vm'
            })   

            .when('/page-2', {
                controller: 'HomeController',
                templateUrl: 'pages/page-2.php',
                controllerAs: 'vm'
            }) 

            .when('/page-3', {
                controller: 'HomeController',
                templateUrl: 'pages/page-3.php',
                controllerAs: 'vm'
            }) 

            .when('/page-4', {
                controller: 'HomeController',
                templateUrl: 'pages/page-4.php',
                controllerAs: 'vm'
            }) 

            .otherwise({ redirectTo: '/login' });
    }

    run.$inject = ['$rootScope', '$location', '$cookieStore', '$http'];
    function run($rootScope, $location, $cookieStore, $http) {
        // keep user logged in after page refresh
        $rootScope.globals = $cookieStore.get('globals') || {};
        if ($rootScope.globals.currentUser) {
            $http.defaults.headers.common['Authorization'] = 'Basic ' + $rootScope.globals.currentUser.authdata; // jshint ignore:line
        }

        $rootScope.$on('$locationChangeStart', function (event, next, current) {
            // redirect to login page if not logged in and trying to access a restricted page
            var restrictedPage = $.inArray($location.path(), ['/login', '/register']) === -1;
            var loggedIn = $rootScope.globals.currentUser;
            if (restrictedPage && !loggedIn) {
                $location.path('/login');
            }
        });
    }

})();