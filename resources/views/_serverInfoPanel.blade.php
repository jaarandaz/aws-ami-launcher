<div class="row" ng-cloak>
    <div class="col-md-8 col-md-offset-2" ng-if="launcher.ec2Instance">
        <div class="panel panel-default" >
            <div class="panel-heading">
                <h3 class="panel-title">Server Info</h3>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr ng-show="!(launcher.ec2Instance.isReady() || launcher.ec2Instance.isTerminated())">
                            <td colspan="2">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="@{{launcher.ec2Instance.percentage()}}" aria-valuemin="0" aria-valuemax="100" style="width: @{{launcher.ec2Instance.percentage()}}%">
                                        <span class="sr-only">@{{launcher.ec2Instance.percentage()}}% Complete</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr ng-class="{'danger' : launcher.ec2Instance.isPending(), 
                                'warning' : launcher.ec2Instance.isInitializing() || launcher.ec2Instance.isShuttingDown(),
                                'success' : launcher.ec2Instance.isReady()}">
                            <th class="text-right">Status</th>
                            <td>
                                    @{{launcher.ec2Instance.status.name}}
                                    <span ng-if="launcher.ec2Instance.isInitializing()">
                                        (instance @{{launcher.ec2Instance.status.instanceStatus}}, system @{{launcher.ec2Instance.status.systemStatus}})
                                    </span>
                                </td>
                        </tr>
                        <tr>
                            <th class="text-right">Public IP</th>
                            <td>
                                <a ng-href="http://@{{launcher.ec2Instance.publicIp}}" target="_blank">@{{launcher.ec2Instance.publicIp}}</a>
                            </td class="text-right">
                        </tr>
                        <tr>
                            <th class="text-right">Instance Type</th>
                            <td>@{{launcher.ec2Instance.instanceType}}</td>
                        </tr>
                        <tr>
                            <th class="text-right">Region</th>
                            <td>@{{launcher.ec2Instance.region}}</td>
                        </tr>
                        <tr>
                            <th class="text-right">Image Id</th>
                            <td>@{{launcher.ec2Instance.imageId}}</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-center">
                                <a ng-hide="launcher.ec2Instance.isTerminated()" class="btn btn-danger" href="" role="button" ng-click="launcher.terminateInstance()" ng-disabled="launcher.terminating">Terminate</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>