
var serviApp = angular.module('servi',
 ['angular-google-analytics',
  'ui.bootstrap',
  'ngRoute',
  'servi.controllers.login',
  'servi.controllers.table',
  'servi.controllers.restaurant',
  'servi.controllers.request',
  'servi.controllers.feedbackStars',
  'servi.controllers.feedbackText',
  'servi.controllers.restaurantMenu',
  'servi.controllers.requestBill',
  'servi.controllers.requestProducts',
  'servi.services.feedback',
  'servi.services.pusher',
  'servi.services.restaurant',
  'servi.services.exceptionOverride',
  'servi.services.requests',
  'servi.services.user',
  'servi.services.products',
  'servi.services.categories'
]);



serviApp.filter("sanitize", ['$sce', function($sce) {
  return function(htmlCode){
    return $sce.trustAsHtml(htmlCode);
  }
}]);


serviApp.config(['$routeProvider', '$httpProvider', 'AnalyticsProvider',
 function($routeProvider, $httpProvider, AnalyticsProvider ) {

     $httpProvider.defaults.withCredentials = true;

     AnalyticsProvider.setAccount('UA-72506076-2');

     $routeProvider.
        when('/loginbytoken', {
             templateUrl: 'templates/login.html',
             controller: 'login'
        }).
        when('/login', {
            templateUrl: 'templates/login.html',
            controller: 'login'
        }).
        when('/restaurant/:restaurantCode', {
            templateUrl: 'templates/restaurant.html',
            controller: 'restaurant'
        }).
        when('/restaurant/:restaurantCode/table/:tableCode', {
            templateUrl: 'templates/table.html',
            controller: 'table'
        }).
        when('/restaurant/:restaurantCode/table/:tableCode/request/:request/:callType/', {
            templateUrl: 'templates/request.html',
            controller: 'request',
            reloadOnSearch: false
        }).
        when('/feedback-stars', {
            templateUrl: 'templates/feedback-stars.html',
            controller: 'feedbackStars'
        }).
        when('/restaurant/:restaurantCode/table/:tableCode/menu', {
            templateUrl: 'templates/restaurantMenu.html',
            controller: 'restaurantMenu'
        }).
        when('/restaurant/:restaurantCode/table/:tableCode/special', {
            templateUrl: 'templates/restaurantSpecial.html',
            controller: 'restaurantSpecial'
        }).
        when('/restaurant/:restaurantCode/table/:tableCode/bill', {
            templateUrl: 'templates/requestBill.html',
            controller: 'requestBill'
        }).
        when('/restaurant/:restaurantCode/table/:tableCode/featured/:productCategory', {
            templateUrl: 'templates/requestProducts.html',
            controller: 'requestProducts'
        }).        
        when('/feedback-text', {
            templateUrl: 'templates/feedback-text.html',
            controller: 'feedbackText'
        }).
        when('/payment', {
            templateUrl: 'templates/payment.html',
            controller: 'payment'
        }).
        otherwise({
            redirectTo: '/login'
        });
 }]);


 serviApp.run(function(Analytics) {});


 function guid() {
   function s4() {
     return Math.floor((1 + Math.random()) * 0x10000)
       .toString(16)
       .substring(1);
   }
   return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
     s4() + '-' + s4() + s4() + s4();
 }
