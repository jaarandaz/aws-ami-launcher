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
                        successCallback(response.data);
                        return;
                    },
                    function(response) {
                        errorCallback(response.data);
                    });
        }

        this.getInstance = function(credentials, instance, successCallback, errorCallback) {
            var params = {credentials : credentials,
                          instanceId  : instance.instanceId};
            
            $http.get(urls.instance, {params})
                .then(
                    function(response) {
                        successCallback(response.data);
                        return;
                    },
                    function(response) {
                        errorCallback(response.data);
                    });
        }

        this.getInstanceStatus = function(credentials, instance, successCallback, errorCallback) {
            var params = {credentials : credentials,
                          instanceId  : instance.instanceId};
            
            $http.get(urls.instanceStatus, {params})
                .then(
                    function(response) {
                        successCallback(response.data);
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

    LauncherController.$inject = ['$scope', '$timeout', 'launcherService'];

    function LauncherController($scope, $timeout, launcherService) {
        var vm = this;

        vm.credentials = {};

        vm.errors = {};
        vm.thereAreErrors = false;

        vm.launchAmi = function(launchForm) {         
            vm.hideErrors();
            launcherService.launchAmi(vm.credentials,
                function(ec2Instance) {
                    vm.ec2Instance = ec2Instance;
                    keepUpdatingUntilReady();
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

        vm.hideErrors = function() {
            vm.errors = {};
            vm.thereAreErrors = false;
        }

        function showErrors(errors) {
            vm.errors = errors;
            vm.thereAreErrors = true;
        }

        function keepUpdatingUntilReady() {
            if (vm.ec2Instance.status.name === 'pending') {
                $timeout(updateInstance, 3000)
            } else if ((vm.ec2Instance.status.instanceStatus === undefined) || (vm.ec2Instance.status.instanceStatus === 'initializing') || 
            (vm.ec2Instance.status.instanceStatus === 'initializing')) {
                $timeout(updateInstanceStatus, 3000)
            }
        }

        function updateInstance() {
            launcherService.getInstance(vm.credentials, vm.ec2Instance,
                function(ec2Instance) {
                    vm.ec2Instance = ec2Instance;
                    keepUpdatingUntilReady();
                },
                function(errors) {
                    showErrors(errors);
                });
        }

        function updateInstanceStatus() {
            launcherService.getInstanceStatus(vm.credentials, vm.ec2Instance,
                function(ec2InstanceStatus) {
                    vm.ec2Instance.status = ec2InstanceStatus;
                    keepUpdatingUntilReady();
                },
                function(errors) {
                    showErrors(errors);
                });
        }

    }

})();
