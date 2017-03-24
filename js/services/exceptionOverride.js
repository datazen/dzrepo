
angular.module('servi.services.exceptionOverride', [])

 .factory('$exceptionHandler', function($injector) {
    return function(exception, cause) {
        var errUrl = apiBaseUrl + "/system/report_js_error";
        StackTrace.fromError(exception)
        .then(function(stackframes) {
            var stringifiedStack = stackframes.map(function(sf) {
                return sf.toString();
            }).join('\n');

            var $http = $injector.get("$http");
            var payload = { stackTrace: stringifiedStack, errorMessage: exception.message };
            $http.post(errUrl , payload)
            .then(function (response) {
                console.log("success")
                console.log(response);
            }, function (response) {
                console.log("fail");
                console.log(response);
            });
        })
        .catch(function(err){
            console.log(err.message);
        });
        throw exception;
    };
});
