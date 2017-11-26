(function () {
    'use strict';

    angular
        .module('app', ['ngRoute', 'ngCookies', 'ngAnimate', 'ngSanitize', 'ngTouch', 'ngMessages', 'ui.bootstrap'])
        .config(config)
        .run(run);

    config.$inject = ['$routeProvider', '$locationProvider'];
    function config($routeProvider, $locationProvider) {
        $routeProvider
            .when('/', {
                redirectTo: '/dashboard'
            })

            .when('/dashboard', {
                controller: 'DashboardController',
                templateUrl: 'pages/dashboard.php',
                controllerAs: 'vm'
            })

            .when('/login', {
                controller: 'LoginController',
                templateUrl: 'pages/login.php',
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

            .when('/editUser/:id', {
                controller: 'UsersController',
                templateUrl: 'pages/editUser.php',
                controllerAs: 'vm'
            }) 

            .when('/accessLevels', {
                controller: 'AccessLevelsController',
                templateUrl: 'pages/accessLevels.php',
                controllerAs: 'vm'
            })   

            .when('/pageAccess', {
                controller: 'PageAccessController',
                templateUrl: 'pages/pageAccess.php',
                controllerAs: 'vm'
            })      

            .when('/configuration/:id', {
                controller: 'ConfigurationController',
                templateUrl: 'pages/configuration.php',
                controllerAs: 'vm'
            })  


            .when('/restricted', {
                controller: 'DashboardController',
                templateUrl: 'pages/restricted.php',
                controllerAs: 'vm'
            })

            .when('/page-1', {
                controller: 'DashboardController',
                templateUrl: 'pages/page-1.php',
                controllerAs: 'vm'
            })   

            .when('/page-2', {
                controller: 'DashboardController',
                templateUrl: 'pages/page-2.php',
                controllerAs: 'vm'
            }) 

            .when('/page-3', {
                controller: 'DashboardController',
                templateUrl: 'pages/page-3.php',
                controllerAs: 'vm'
            }) 

            .when('/page-4', {
                controller: 'DashboardController',
                templateUrl: 'pages/page-4.php',
                controllerAs: 'vm'
            }) 

            .otherwise({ redirectTo: '/login' });
    }

    run.$inject = ['$rootScope', '$location', '$cookieStore', '$http', 'AccessService'];
    function run($rootScope, $location, $cookieStore, $http, AccessService) {  
        // keep user logged in after page refresh
        $rootScope.globals = $cookieStore.get('globals') || {};
        if ($rootScope.globals.currentUser) {            
            $http.defaults.headers.common['Authorization'] = 'Basic ' + $rootScope.globals.currentUser.authdata; // jshint ignore:line
        }

        $rootScope.$on('$locationChangeStart', function (event, next, current) {
            // redirect to login page if not logged in and trying to access a restricted page
            var thisRoute = [];
            var restrictedPage = $.inArray($location.path(), ['/login']) === -1;
            var loggedIn = $rootScope.globals.currentUser;
            if (restrictedPage) {
                if (!loggedIn) {  // if not logged in goto login
                    $location.path('/login');
                } else { // get the route access level from the db
                    var thisPath = ($location.path() == '/') ? '/dashboard' : $location.path();
                    AccessService.GetPageByRoute(thisPath)
                        .then(function (route) {
                            thisRoute = route.data;
                            if (thisRoute != undefined) {
                                // check access of user against the level required to view the page
                                if ($rootScope.globals.currentUser.accessLevel >= thisRoute.level || $rootScope.globals.currentUser.accessLevel == 5) {
                                    // authorized
                                } else { 
                                    $location.path('/restricted');  // show restricted page
                                }
                            } else {
                                if ($rootScope.globals.currentUser.accessLevel != 5) {
                                    $location.path('/restricted');  // show restricted page
                                }
                            }
                        });
                }
            }
        });
    }

})();