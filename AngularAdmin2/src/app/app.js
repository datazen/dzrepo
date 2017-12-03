(function () {
    'use strict';

    angular
        .module('app', ['ngRoute', 'ngCookies', 'ngAnimate', 'ngSanitize', 'ngTouch', 'ngMessages', 'ui.bootstrap', 'ui.router'])
        .config(config)
        .run(run);

    config.$inject = ['$routeProvider', '$stateProvider'];
    function config($routeProvider, $stateProvider) {
       /*
        * Frontend Routes (state=public)
        */
        $stateProvider
        .state('public', {
            abstract: true,
            site: 'Store',
            module: 'public'
        })
        .state('public.login', {
            url: '/login',
            site: 'Store',           
            module: 'public'
        })
        .state('public.home', {
            url: '/home',
            site: 'Store',            
            module: 'public'
        })
        .state('public.account', {
            url: '/account',
            site: 'Store',            
            module: 'public'
        });

        $routeProvider  
        .when('/', {
            redirectTo: '/home',
        })
        .when('/login', {
            controller: 'StoreLoginController',
            templateUrl: 'app/components/Store/login/loginView.php',
            controllerAs: 'vm'
        })
        .when('/home', {
            controller: 'StoreHomeController',
            templateUrl: 'app/components/Store/home/homeView.php',
            controllerAs: 'vm'
        })
        .when('/account', {
            controller: 'StoreAccountController',
            templateUrl: 'app/components/Store/account/accountView.php',
            controllerAs: 'vm'
        });
       /*
        * Admin Routes (state=private)
        */ 
        $stateProvider                    
            .state('Admin', {
                abstract: true,
                module: 'private'
            })
            .state('Admin.dashboard', {
                url: '/Admin/dashboard',
                module: 'private'
            })
            .state('Admin.login', {
                url: '/Admin/login',
                module: 'private'
            })
            .state('Admin.profile', {
                url: '/Admin/profile',
                module: 'private'
            }) 
            .state('Admin.users', {
                url: '/Admin/users',
                module: 'private'
            })  
            .state('Admin.addUser', {
                url: '/Admin/addUser',
                module: 'private'
            })     
            .state('/Admin.editUser', {
                url: '/Admin/editUser/:id',
                module: 'private'
            })
            .state('Admin.accessLevels', {
                url: '/Admin/accessLevels',
                module: 'private'
            })   
            .state('Admin.pageAccess', {
                url: '/Admin/pageAccess',
                module: 'private'
            })      
            .state('Admin.configuration/:id', {
                url: '/Admin/configuration',
                module: 'private'
            })  
            .state('Admin.restricted', {
                url: '/Admin/restricted',
                module: 'private'
            })
            .state('Admin.page-1', {
                url: '/Admin/page-1',
                module: 'private'
            })    
            .state('Admin.page-2', {
                url: '/Admin/page-2',
                module: 'private'
            }) 
            .state('Admin.page-3', {
                url: '/Admin/page-3',
                module: 'private'
            }) 
            .state('Admin.page-4', {
                url: '/Admin/page-4',
                module: 'private'
            });
        
        $routeProvider 
            .when('/Admin', { 
                redirectTo: '/Admin/dashboard' 
            })
            .when('/Admin/dashboard', {
                controller: 'AdminDashboardController',
                templateUrl: 'app/components/Admin/dashboard/dashboardView.php',
                controllerAs: 'vm'
            })
            .when('/Admin/login', {
                controller: 'AdminLoginController',
                templateUrl: 'app/components/Admin/login/loginView.php',
                controllerAs: 'vm'
            })
            .when('/Admin/profile', {
                controller: 'AdminProfileController',
                templateUrl: 'app/components/Admin/profile/profileView.php',
                controllerAs: 'vm'
            }) 
            .when('/Admin/users', {
                controller: 'AdminUsersController',
                templateUrl: 'app/components/Admin/users/usersView.php',
                controllerAs: 'vm'
            })  
            .when('/Admin/addUser', {
                controller: 'AdminUsersController',
                templateUrl: 'app/components/Admin/users/addUserView.php',
                controllerAs: 'vm'
            })     
            .when('/Admin/editUser/:id', {
                controller: 'AdminUsersController',
                templateUrl: 'app/components/Admin/users/editUserView.php',
                controllerAs: 'vm'
            }) 
            .when('/Admin/accessLevels', {
                controller: 'AdminAccessLevelsController',
                templateUrl: 'app/components/Admin/accessLevels/accessLevelsView.php',
                controllerAs: 'vm'
            })   
            .when('/Admin/pageAccess', {
                controller: 'AdminPageAccessController',
                templateUrl: 'app/components/Admin/pageAccess/pageAccessView.php',
                controllerAs: 'vm'
            })      
            .when('/Admin/configuration/:id', {
                controller: 'AdminConfigurationController',
                templateUrl: 'app/components/Admin/configuration/configurationView.php',
                controllerAs: 'vm'
            })  
            .when('/Admin/restricted', {
                controller: 'AdminDashboardController',
                templateUrl: 'app/components/Admin/restricted/restrictedView.php',
                controllerAs: 'vm'
            })
            .when('/Admin/page-1', {
                controller: 'AdminDashboardController',
                templateUrl: 'app/components/Admin/dashboard/page1View.php',
                controllerAs: 'vm'
            })   
            .when('/Admin/page-2', {
                controller: 'AdminDashboardController',
                templateUrl: 'app/components/Admin/dashboard/page2View.php',
                controllerAs: 'vm'
            }) 
            .when('/Admin/page-3', {
                controller: 'AdminDashboardController',
                templateUrl: 'app/components/Admin/dashboard/page3View.php',
                controllerAs: 'vm'
            }) 
            .when('/Admin/page-4', {
                controller: 'AdminDashboardController',
                templateUrl: 'app/components/Admin/dashboard/page4View.php',
                controllerAs: 'vm'
            }) 
            .otherwise({ redirectTo: '/' });
    } 

    run.$inject = ['$rootScope', '$location', '$cookieStore', '$http', 'AdminPageAccessService', '$state'];
    function run($rootScope, $location, $cookieStore, $http, AdminPageAccessService, $state) {  
        // keep user logged in after page refresh
        $rootScope.globals = $cookieStore.get('globals') || {};
        if ($rootScope.globals.currentUser) {            
            $http.defaults.headers.common['Authorization'] = 'Basic ' + $rootScope.globals.currentUser.authdata;
        }

        $rootScope.frontend = $cookieStore.get('frontend') || {};
        if ($rootScope.frontend.currentCustomer) {
            $http.defaults.headers.common['Authorization'] = 'Basic ' + $rootScope.frontend.currentCustomer.authdata;
        }

        $rootScope.$on('$stateChangeStart', function(e, toState, toParams, fromState, fromParams) {

            $rootScope.currentState = toState
//            alert(print_r($rootScope.currentState, true));

            if($rootScope.currentState.module && $rootScope.currentState.module == 'private') {  // Admin
                // redirect to login page if not logged in and trying to access a restricted page
                var thisRoute = [];
                var restrictedPage = $.inArray($location.path(), ['/Admin/login']) === -1;
                var loggedIn = $rootScope.globals.currentUser;
                if (restrictedPage) {
                    if (!loggedIn) {  // if not logged in goto login
                        $location.path('/Admin/login');
                    } else { // get the route access level from the db
                        var thisPath = ($location.path() == '/Admin') ? '/Admin/login' : $location.path();
                        AdminPageAccessService.GetPageByRoute(thisPath)
                            .then(function (route) {
                                thisRoute = route.data;
                                if (thisRoute != undefined) {
                                    // check access of user against the level required to view the page
                                    if ($rootScope.globals.currentUser.accessLevel >= thisRoute.level || $rootScope.globals.currentUser.accessLevel == 5) {
                                        // authorized
                                    } else { 
                                        $location.path('/restricted');  // show restricted page
                                    }
                                } 
                            });
                    }
                }
            } else { // Public Frontend

                // redirect to login page if not logged in and trying to access a restricted page
                var restrictedPage = $.inArray($location.path(), ['/login']) === -1;
                var loggedIn = $rootScope.frontend.currentCustomer;
                if (restrictedPage && !loggedIn) {
                    $location.path('/login');
                }

            }
        });


    }    
 

})();