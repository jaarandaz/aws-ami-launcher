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
