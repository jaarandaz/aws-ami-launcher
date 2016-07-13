'use strict';

(function() {

    angular
        .module('app', []);

})();

(function() {

    angular
        .module('app')
        .service('launcherService', launcherService);

    launcherService.$inject = ['$http', '$window'];

    function launcherService($http, $window) {

        var urls = $window.launcher.urls;

        this.launchAmi = function(credentials, successCallback, errorCallback) {
            $http.post(urls.launchAmi, {credentials})
                .then(
                    function(response) {
                        console.log("success");
                        console.log(response);
                        successCallback(response);
                        return;
                    },
                    function(response) {
                        console.log(response);
                        console.log(response.data['credentials.accessKey']);
                        console.log(response.data['credentials.secretKey']);
                        errorCallback(response);
                    });
        }
    }

})();

(function() {

    angular
        .module('app')
        .controller('LauncherController', LauncherController);

    LauncherController.$inject = ['$scope', 'launcherService'];

    function LauncherController($scope, launcherService) {
        var vm = this;

        vm.credentials = {};

        vm.launchAmi = function(launchForm) {
            launchForm.$setValidity("accessKey", false);
            
            /*launcherService.launchAmi(vm.credentials,
                function() {
                    console.log("hey controller");
                },
                function() {

                    console.log("error handler");
                });
            */
        }
    }

})();

//# sourceMappingURL=app.js.map
