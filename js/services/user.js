angular.module('servi.services.user', [])
.service('User', function( $rootScope ) {
return {

     location: null,

     initialized: false,

     checkState: function() {
         if(!this.initialized) {
             var that = this;
             navigator.geolocation.getCurrentPosition(function(location) {
                 that.location = location;
             }, function(error) {
             }, {
                 enableHighAccuracy: true,
                 timeout: 5000,
                 maximumAge: 0
             });
         }
     }
}
});
