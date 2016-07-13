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
            $http.post(urls.launchAmi, {})
                .then(
                    function(response) {
                        console.log("success");
                        console.log(response);
                        successCallback(response);
                        return;
                    },
                    function(response) {
                        errorCallback(response.data);
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

        vm.errors = {};
        vm.thereAreErrors = false;

        vm.launchAmi = function(launchForm) {         
            hideErrors();
            launcherService.launchAmi(vm.credentials,
                function() {
                },
                function(errors) {
                    console.log(errors);
                    if (errors.hasOwnProperty('accessKey')) {
                        launchForm.$setValidity("accessKey", false);    
                    }
                    if (errors.hasOwnProperty('secretKey')) {
                        launchForm.$setValidity("secretKey", false);
                    }
                    showErrors(errors);
                }
            );
        }

        function showErrors(errors) {
            vm.errors = errors;
            vm.thereAreErrors = true;
        }

        function hideErrors() {
            vm.errors = {};
            vm.thereAreErrors = false;
        }
    }

})();
