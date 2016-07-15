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
