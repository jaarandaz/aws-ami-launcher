<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/lib/bootstrap.css')}}">
        <title>Launcher</title>
    </head>
    <body>
        <div class="container" ng-app="app" ng-controller="LauncherController as launcher">
            <div class="page-header">
                <h1>AWS AMI Laucher</h1>
            </div>
            <div class="row" ng-cloak>
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Insert your AWS credentials</h3>
                        </div>
                        <div class="panel-body">
                            <form name="launchForm" class="form-horizontal" ng-submit="launcher.launchAmi(launchForm)">
                                <div class="form-group" ng-class="{'has-error' : launcher.credentials.accessKey.$invalid}">
                                    <label for="accesKey" class="col-sm-3 control-label">Access Key ID</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="accessKey" placeholder="AWS Access Key" ng-model="launcher.credentials.accessKey" required>
                                    </div>
                                </div>
                                <div class="form-group" ng-class="{'has-error' : launcher.credentials.secretKey.$invalid}">
                                    <label for="secretKey" class="col-sm-3 control-label">Secret Key</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="secretKey" placeholder="Secret Access Key" ng-model="launcher.credentials.secretKey" required>
                                    </div>
                                </div>
                                <div class="alert alert-danger ng-hide" ng-show="launcher.thereAreErrors" role="alert">
                                    <button type="button" class="close" aria-label="Close" ng-click="launcher.hideErrors()">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <ul>
                                        <li ng-repeat="(field, message) in launcher.errors">
                                            @{{message[0]}}
                                        </li>
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary" ng-disabled="launcher.launchStarted">Launch!</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" ng-cloak>
                <div class="col-md-8 col-md-offset-2" ng-if="launcher.ec2Instance">
                    <div class="panel panel-default" >
                        <div class="panel-heading">
                            <h3 class="panel-title">Server Info</h3>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr ng-show="!launcher.ec2Instance.isReady()">
                                        <td colspan="2">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="@{{launcher.ec2Instance.percentage()}}" aria-valuemin="0" aria-valuemax="100" style="width: @{{launcher.ec2Instance.percentage()}}%">
                                                    <span class="sr-only">@{{launcher.ec2Instance.percentage()}}% Complete</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr ng-class="{'danger' : launcher.ec2Instance.isPending(), 
                                            'warning' : launcher.ec2Instance.isInitializing(),
                                            'success' : launcher.ec2Instance.isReady()}">
                                        <th class="text-right">Status</th>
                                        <td>
                                                @{{launcher.ec2Instance.status.name}}
                                                <span ng-if="!launcher.ec2Instance.isPending()">
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <p class="text-muted text-center"><small>Jose Ant. Aranda</small></p>
            </div>
        </footer>

        <script type="text/javascript" src="{{asset('js/lib/jquery.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/lib/bootstrap.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/lib/angular.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/app.js')}}"></script>

        <script type="text/javascript">
            var launcher = {
                    urls : {
                        launchAmi : "{{route('launcher.launchAmi')}}",
                        instance : "{{route('launcher.instance')}}",
                        instanceStatus : "{{route('launcher.instanceStatus')}}"
                    }};
        </script>
    </body>
</html>