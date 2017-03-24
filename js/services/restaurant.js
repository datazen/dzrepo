angular.module('servi.services.restaurant', [])
.service('Restaurant', function( $location , $q, $http, $timeout, $window, Requests, Analytics, User ){
  return {
      waiter: {
          userpic : 'noprofilepics-male.png',
          name    : 'Unknown',
          initialized: false,
      },
      restaurant: {
          name            : 'Unknown restaurant',
          isPaypalEnabled : false,
          logo            : null,
          token           : 'unknown',
          initialized     : false,
      },
      table: {
          id          : 0,
          tableNumber : 0,
          token       : 0,
          initialized : false,

      },


      checkState: function(restaurantToken, tableToken) {
          var reloadPromise = $q.defer();
          var that = this;
          if( !this.initialized() ) {
              this._reload_state(reloadPromise);
          } else {
              reloadPromise.resolve(true);
          }
          return reloadPromise.promise;
      },

      _reload_state: function(promise) {
          var that = this;
          $http.post(apiBaseUrl + "/rest/login/relogin")
          .then(function (response) {
               console.log(response);
               that.initRestarauntData(response.data.restaurant);
               if(response.data.table != null ) {
                   that.initTableData(response.data.table);
                   that.initWaiterData(response.data.table);
               }
               User.checkState();
               promise.resolve(true);
           }, function (response) {
               that.logout();
               promise.resolve(false);
           });
      },

      initialized: function() {
        return this.waiter.initialized &&
               this.restaurant.initialized &&
               this.table.initialized;
      },

      initRestarauntData: function(container) {
          this.restaurant.name            = container.restaurantName;
          this.restaurant.isPaypalEnabled = container.isPaypalEnabled;
          this.restaurant.logo            = container.restaurantPicUrl;
          this.restaurant.token           = container.token;
          this.restaurant.initialized     = true;
          this.restaurant.actions         = container.actions;
          this.restaurant.mode            = container.restaurantMode;
          this.restaurant.map             = {
                                              url: container.restaurantMapUrl || false,
                                              height: window.screen.height - 120
                                            };

      },

      initTableData: function(container) {
          this.table.id           = container.tableId;
          this.table.tableNumber  = container.tableNumber;
          this.table.token        = container.token;
          this.table.initialized  = true;
      },

      initWaiterData: function(container) {
          this.waiter.name        = container.waiterName;
          this.waiter.userpic     = container.waiterPicUrl || this.waiter.userpic;
          this.waiter.initialized = true;
      },

      logout: function() {
          console.log('logout');
          $http.post(apiBaseUrl + "/rest/login/logout")
          .then(function (response) {
              Analytics.trackEvent('logout', 'logout', 'success');
          }, function (response) {
              Analytics.trackEvent('logout', 'logout', 'fail');
          });

          $location.path("/login");
          $timeout(function() {
            $window.location.reload(true);
          });
      },

      goToTable: function() {
          $location.path("/restaurant/" + this.restaurant.token +
                         "/table/"      + this.table.token )
                         .search("status",null);
      },

      goToTableOverHTTPS: function() {
          var pathname = $window.location.pathname;
          pathname = pathname.substring(0, pathname.length - 3);
          var url = "http://" + $window.location.host  + pathname + "#"+
                  "/restaurant/" + this.restaurant.token +
                  "/table/"      + this.table.token
           $window.location.href= url;
      },

      goToMenu: function() {
          $location.path("/restaurant/" + this.restaurant.token +
                         "/table/"      + this.table.token +
                         "/menu");
      },

      goToSpecial: function() {
          var url = "http://" + $window.location.host  + $window.location.pathname + "s/#"+
                  "/restaurant/" + this.restaurant.token +
                  "/table/"      + this.table.token +
                  "/special";
                  console.log(url);
           $window.location.href= url;
      },

      goToRestaurant: function() {
          $location.path("/restaurant/" + this.restaurant.token);
      },

      goToNotification: function(notification) {
          $location.path("/restaurant/" + this.restaurant.token +
                         "/table/"      + this.table.token +
                         "/request/"    + notification.uuid +
                         "/"            + notification.callType + "/")
                    .search("status","waiting")
                    .search("payment", notification.data.paymentMethod);
      },

      goToRequestBill: function () {
          $location.path(
              "/restaurant/" + this.restaurant.token +
              "/table/"      + this.table.token +
              "/bill");
      },

      goToRequestFeaturedProducts: function(category) {
          $location.path(
              "/restaurant/" + this.restaurant.token +
              "/table/"      + this.table.token +
              "/featured/"      + category);
      },      

      buttonEnabled: function(button) {
          var button = this.getAction(button);
          return button && button.enabled;
      },

      captionForButton: function(button, defaultCaption) {
          var button = this.getAction(button);
          return button && button.customCaption != null && button.customCaption != '' ? button.customCaption : defaultCaption;
      },

      getAction: function(actionName) {
          if(!this.restaurant || !this.restaurant.actions) {
              return null;
          }

          var action = null;
          this.restaurant.actions.forEach(function(act){
              if(act.action === actionName) {
                 action = act;
              }
          });

          return action;
      }
  }
});
