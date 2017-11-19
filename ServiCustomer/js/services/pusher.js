angular.module('servi.services.pusher', [])

.service('Pusher', function( $rootScope ) {
 return {
    PUSHER_TOKEN : 'ae36a99eb10f0af80628',
    pusher : null,
    tableChannel: null,

    connect: function(tableId) {
        Pusher.log = function(message) {
          if (window.console && window.console.log) {
              window.console.log(message);
          }
        };

        this.pusher = new Pusher(this.PUSHER_TOKEN, { encrypted: true });
        this.tableChannel = this.pusher.subscribe('table-' + tableId);
    },

    disconnect: function() {
        if(this.pusher != null) {
            this.pusher.disconnect();
            this.tableChannel = null;
        }
    },

    bind: function(callback) {
        this.tableChannel.bind('table', callback);
    },

    unbind: function(callback) {
        if(this.tableChannel) {
            this.tableChannel.unbind('table', callback);
        }
    }
 }
});
