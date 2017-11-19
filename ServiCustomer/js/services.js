angular.module('servi.services', [])

.service('Restaurant', function(){
  return {
  }
})

.service('Pusher', function( $rootScope ) {
 return {
    PUSHER_TOKEN : 'ae36a99eb10f0af80628',
    pusher : null,
    tableChannel: null,

    connectToServer: function(tableId) {
        Pusher.log = function(message) {
          if (window.console && window.console.log) {
              window.console.log(message);
          }
        };

        this.pusher = new Pusher(this.PUSHER_TOKEN, { encrypted: true });
        this.tableChannel = this.pusher.subscribe('table-' + tableId);
    },

    bind: function(callback) {
        this.tableChannel.bind('table', callback);
    },

    unbind: function(callback) {
        this.tableChannel.unbind('table', callback);
    },

 }
})
 
.service('Feedback', function ( $http )  {
        return  {
            state : { csatSubmitted: false },
            saveCSAT: function( userVote  ) {
                var that = this;
                $http.post( apiBaseUrl + "/rest/saveCSATRating", { vote: userVote } )
                .then(function (response) {
                    that.state.csatSubmitted = true;
                }, function (response) {
                    alert("can't save feedback");
                });
            }
        }
})
