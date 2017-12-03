(function () {
    'use strict';

    angular
        .module('app', ['ngRoute', 'ngCookies', 'ngAnimate', 'ngSanitize', 'ngTouch', 'ngMessages', 'ui.bootstrap', 'ui.router', 
                        'pascalprecht.translate', 'adminRoutes', 'siteRoutes', 'english', 'spanish', 'french', 'german'])
        .config(config)
        .run(run);

    config.$inject = ['$routeProvider', '$stateProvider', '$translateProvider'];
    function config($routeProvider, $stateProvider, $translateProvider) {
        // Admin routes are in app/routes/app.routes.Admin.js
        // Site routes are in app/routes/app.routes.Site.js
        $routeProvider.otherwise({ redirectTo: '/' });

        $translateProvider.preferredLanguage('en');        

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

            if($rootScope.currentState.site && $rootScope.currentState.site == 'Admin') {  
                // redirect to login page if not logged in and trying to access a restricted page
                var thisRoute = [];
                //var restrictedPage = $.inArray($location.path(), ['/Admin/login']) === -1;
                var restrictedPage = ($rootScope.currentState.module && $rootScope.currentState.module == 'private') ? true : false;
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
                                    if ($rootScope.globals.currentUser.accessLevel && ($rootScope.globals.currentUser.accessLevel >= thisRoute.level || $rootScope.globals.currentUser.accessLevel >= 5)) {
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
                //var restrictedPage = ($rootScope.currentState.module && $rootScope.currentState.module == 'private') ? true : false;               
                //var restrictedPage = $.inArray($location.path(), ['/login']) === -1;
                //var loggedIn = $rootScope.frontend.currentCustomer;
                //if (restrictedPage) {
                //    $location.path('/login');
                //}

            }
        });


    }    
 

})();