angular.module('servi.special.restaurant', [])
.service('Restaurant', function( $location , $q, $http, $timeout, $window, Requests, Analytics, User ){
  return {

      goToTableOverHTTPS: function() {
          var url = "https://"     + $window.location.host  + $window.location.pathname + "#"+
                    "/restaurant/" + this.restaurant.token +
                    "/table/"      + this.table.token
           $window.location.href= url;
      }
  }
});
