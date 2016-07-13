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
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default" ng-cloak>
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
                                        <input type="text" class="form-control" name="secretKey" placeholder="Secret Access Key" ng-model="launcher.credentials.secretKey" required>
                                    </div>
                                </div>
                                <div class="alert alert-danger ng-hide" ng-show="launcher.thereAreErrors" role="alert">
                                    <ul>
                                        <li ng-repeat="(field, message) in launcher.errors">
                                            @{{message[0]}}
                                        </li>
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary" >Launch!</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <p class="text-muted text-center"><small>Jose Ant. Aranda.</small></p>
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
                    }};
        </script>
    </body>
</html>