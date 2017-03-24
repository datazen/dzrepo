
var serviApp = angular.module('servi.special',
 ['angular-google-analytics',
  'ui.bootstrap',
  'ngRoute',
  'servi.special.controllers.restaurantSpecial',
  'servi.services.feedback',
  'servi.services.pusher',
  'servi.services.restaurant',
  'servi.services.exceptionOverride',
  'servi.services.requests',
  'servi.services.user'
]);

serviApp.config(['$routeProvider', '$httpProvider', 'AnalyticsProvider',
 function($routeProvider, $httpProvider, AnalyticsProvider ) {
     $httpProvider.defaults.withCredentials = true;
     AnalyticsProvider.setAccount('UA-72506076-2');
     $routeProvider.
        when('/restaurant/:restaurantCode/table/:tableCode/special', {
            templateUrl: 'restaurantSpecial.html',
            controller: 'restaurantSpecial'
        }).
        otherwise({
            redirectTo: '/login'
        });
 }]);

serviApp.run(function(Analytics) {});
