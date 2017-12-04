(function () {
    'use strict';

    angular
        .module('adminRoutes', ['ngRoute', 'ui.router'])
        .config(config);
    
    config.$inject = ['$routeProvider', '$stateProvider'];
    function config($routeProvider, $stateProvider) {
       /*
        * Admin Routes (state=private)
        */ 
        $stateProvider                    
            .state('Admin', {
                abstract: true,
                site: 'Admin',
                module: 'private'
            })
            .state('Admin.dashboard', {
                url: '/Admin/dashboard',
                site: 'Admin',               
                module: 'private'
            })
            .state('Admin.login', {
                url: '/Admin/login',
                site: 'Admin',                
                module: 'public'
            })
            .state('Admin.profile', {
                url: '/Admin/profile',
                site: 'Admin',
                module: 'private'
            }) 
            .state('Admin.users', {
                url: '/Admin/users',
                site: 'Admin',               
                module: 'private'
            })  
            .state('Admin.addUser', {
                url: '/Admin/addUser',
                site: 'Admin',                
                module: 'private'
            })     
            .state('/Admin.editUser', {
                url: '/Admin/editUser/:id',
                site: 'Admin',                
                module: 'private'
            })
            .state('Admin.accessLevels', {
                url: '/Admin/accessLevels',
                site: 'Admin',                
                module: 'private'
            })   
            .state('Admin.pageAccess', {
                url: '/Admin/pageAccess',
                site: 'Admin',                
                module: 'private'
            })      
            .state('Admin.configuration/:id', {
                url: '/Admin/configuration',
                site: 'Admin',                
                module: 'private'
            })  
            .state('Admin.restricted', {
                url: '/Admin/restricted',
                site: 'Admin',                
                module: 'private'
            })
            .state('Admin.company', {
                url: '/Admin/company',
                site: 'Admin',                
                module: 'private'
            })            
            .state('Admin.page-1', {
                url: '/Admin/page-1',
                site: 'Admin',               
                module: 'private'
            })    
            .state('Admin.page-2', {
                url: '/Admin/page-2',
                site: 'Admin',
                module: 'private'
            }) 
            .state('Admin.page-3', {
                url: '/Admin/page-3',
                site: 'Admin',                
                module: 'private'
            }) 
            .state('Admin.page-4', {
                url: '/Admin/page-4',
                site: 'Admin',                
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
            .when('/Admin/company', {
                controller: 'AdminCompanyController',
                templateUrl: 'app/components/Admin/company/companyView.php',
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
            }); 
    }   

})();