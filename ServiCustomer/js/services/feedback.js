angular.module('servi.services.feedback', [])
.service('Feedback', function ( $http, Restaurant )  {
        return  {
            waiterRating: 0,
            serviRating: 0,

            saveWaiterRating: function(stars) {
                this.waiterRating = stars;
                $http.post( apiBaseUrl + "/rest/saveRating", { waiterVote: stars } )
                .then(function (response) {
                }, function (response) {
                });
            },
            saveServiRating: function(stars) {
                this.serviRating = stars;
                $http.post( apiBaseUrl + "/rest/saveRating", { serviVote: stars } )
                .then(function (response) {
                }, function (response) {
                });
            },
            saveTextFeedback: function(textFeedback) {
                $http.post( apiBaseUrl + "/rest/saveRating", { text: textFeedback } )
                .then(function (response) {
                    Restaurant.logout();
                }, function (response) {
                });
            }
        }
})
