angular.module('servi.services.requests', ['servi.services.pusher'])

.service('Requests', function( Pusher, Analytics, $http, $rootScope ) {
 return {
     activeRequest: null,
     error: null,
     callback: null,
     init: function(tableId) {
         if( this.callback != null ) {
             return;
         }
         console.log("Requests init with table id " + tableId );
         Pusher.connect(tableId);
         var that = this;
         this.callback = function(data) { that.__onNotification(data); };
         Pusher.bind( this.callback  );
     },

     reset: function() {
         this.callback = null;
         this.error = null;
         this.activeRequest = null;
         Pusher.unbind( this.callback);
         Pusher.disconnect();
     },

     /**
      *
      * @param type
      * @param data
      * @param [data.payment]
      * @param [data.type]
      * @returns {{uuid: *, callType: *, status: string, data: (*|{})}}
      */
     request: function(type, data) {
         data = data || {
             //payment: 0,
             //tip: 0
         };

         var notificationUUID = guid(),
             notification = {
                 uuid    : notificationUUID,
                 callType: type,
                 status  : 'sending',
                 data: data
             };

         this.activeRequest = notification;
         this.error = null;


         //Analytics.trackEvent('menu', 'call-status', 'try');
         $http.post(apiBaseUrl + "/rest/call" , {
             type : type,
             guid: notificationUUID,
             data: notification.data
         })
         .then(function (response) {
             Analytics.trackEvent('menu', 'call-success', 'fail');
             console.log(" call success" + type);
             console.log(response);
             notification.status = "waiting";
         }, function (response) {
             Analytics.trackEvent('menu', 'call-status', 'fail');
             notification.status = "error";
             console.log("call fail" + type);
             console.log(response);
         });
         return notification;
     },

     __onNotification: function(data) {
         console.log("broadcasting new notification...");
         console.log(data);

         var serverNotification = JSON.parse(data);
         console.log(this);
         if( this.activeRequest != null &&
             this.activeRequest.uuid === serverNotification.uuid)
         {
             console.log("confirmed notification");
             this.activeRequest.status = "confirmed";
         }

         $rootScope.$broadcast('notification', data);
     }

 }
});
