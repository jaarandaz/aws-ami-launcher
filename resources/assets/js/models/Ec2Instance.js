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