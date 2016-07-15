'use strict';

(function() {

    angular
        .module('app', []);

})();

(function() {

    angular
        .module('app')
        .factory('Ec2Instance', Ec2Instance);

    function Ec2Instance() {
        var Ec2Instance = function(data) {

            angular.extend(this, {
                isPending : function() {
                    return (this.status.name === 'pending');
                },
                isInitializing : function() {
                    return ((this.status.name === 'running') &&
                            (this.status.instanceStatus !== 'ok') ||
                            (this.status.systemStatus !== 'ok'));
                },
                isShuttingDown : function() {
                    return (this.status.name === 'shutting-down');
                },
                isReady : function() {
                    return ((this.status.name === 'running') &&
                            (this.status.instanceStatus === 'ok') &&
                            (this.status.systemStatus === 'ok'));
                },
                isTerminated : function() {
                    return (this.status.name === 'terminated');
                },
                percentage : function() {
                    if (this.isPending()) {
                        return 25;
                    }
                    if (this.isShuttingDown()) {
                        return 50;
                    }
                    if (((this.status.instanceStatus === 'initializing') &&
                         (this.status.systemStatus === 'initializing')) ||
                        ((this.status.instanceStatus === undefined) &&
                        (this.status.systemStatus === undefined))) {
                        return 50;
                    }
                    if (((this.status.instanceStatus === 'initializing') &&
                                (this.status.systemStatus === 'ok')) ||
                               ((this.status.systemStatus === 'initializing') &&
                                (this.status.instanceStatus === 'ok'))) {
                        return 75;
                    }

                    if (this.isReady()) {
                        return 100;
                    }
                }
            });
            angular.extend(this, data);
        };

        return Ec2Instance;
    }

})();

(function() {

    angular
        .module('app')
        .service('launcherService', launcherService);

    launcherService.$inject = ['$http', '$window', 'Ec2Instance'];

    function launcherService($http, $window, Ec2Instance) {

        var urls = $window.launcher.urls;

        this.launchAmi = function(credentials, successCallback, errorCallback) {
            $http.post(urls.launchAmi, {credentials})
                .then(
                    function(response) {
                        successCallback(new Ec2Instance(response.data));
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
                        var newInstance = new Ec2Instance(response.data);
                        newInstance.securityGroupId = instance.securityGroupId;
                        successCallback(newInstance);
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
                        instance.status = response.data;
                        successCallback(instance);
                        return;
                    },
                    function(response) {
                        errorCallback(response.data);
                    });
        }

        this.terminateInstance = function(credentials, instance, successCallback, errorCallback) {
            var params = {credentials     : credentials,
                          instanceId      : instance.instanceId};
            
            $http.post(urls.terminateInstance, params)
                .then(
                    function(response) {
                        instance.status = response.data;
                        successCallback(instance);
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

    LauncherController.$inject = ['$scope', '$window', '$timeout', 'launcherService'];

    function LauncherController($scope, $window, $timeout, launcherService) {
        var vm = this;

        vm.credentials = {};
        vm.showCredentials = true;

        vm.launching = false;
        vm.terminating = false;

        vm.errors = {};
        vm.thereAreErrors = false;

        $window.onbeforeunload = function(){
          return 'Are you sure you want to leave?';
        };

        vm.launchAmi = function(launchForm) {         
            vm.hideErrors();
            vm.launching = true;
            launcherService.launchAmi(vm.credentials,
                function(ec2Instance) {
                    vm.ec2Instance = ec2Instance;
                    vm.showCredentials = false;
                    keepUpdatingUntilFinalStatus();
                },
                function(errors) {
                    console.log(errors);
                    if (errors.hasOwnProperty('accessKey')) {
                        launchForm.$setValidity("accessKey", false);    
                    }
                    if (errors.hasOwnProperty('secretKey')) {
                        launchForm.$setValidity("secretKey", false);
                    }
                    vm.launching = false;
                    showErrors(errors);
                }
            );
        }

        vm.terminateInstance = function(launchForm) {         
            vm.hideErrors();
            vm.terminating = true;
            launcherService.terminateInstance(vm.credentials, vm.ec2Instance,
                function(ec2Instance) {
                    vm.ec2Instance = ec2Instance;
                    keepUpdatingUntilFinalStatus();
                },
                function(errors) {
                    console.log(errors);
                    vm.terminating = false;
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

        function keepUpdatingUntilFinalStatus() {
            if (vm.ec2Instance.isPending() || vm.ec2Instance.isShuttingDown()) {
                $timeout(updateInstance, 3000)
            } else if (vm.ec2Instance.isInitializing()) {
                $timeout(updateInstanceStatus, 3000)
            }
        }

        function updateInstance() {
            launcherService.getInstance(vm.credentials, vm.ec2Instance,
                function(ec2Instance) {
                    vm.ec2Instance = ec2Instance;
                    if (vm.ec2Instance.isTerminated()) {
                        vm.terminating = false;
                        vm.launching = false;
                    } else {
                        keepUpdatingUntilFinalStatus();    
                    }
                },
                function(errors) {
                    showErrors(errors);
                });
        }

        function updateInstanceStatus() {
            if (vm.ec2Instance.isInitializing()) {
                launcherService.getInstanceStatus(vm.credentials, vm.ec2Instance,
                    function(ec2Instance) {
                        vm.ec2Instance = ec2Instance;
                        keepUpdatingUntilFinalStatus();
                    },
                    function(errors) {
                        showErrors(errors);
                    });
            }
        }

    }

})();

//# sourceMappingURL=app.js.map
