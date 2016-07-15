<div class="row" ng-cloak>
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="gtn-toolbar pull-right">
                    <span ng-show="launcher.showCredentials" class="glyphicon glyphicon-chevron-up" ng-click="launcher.showCredentials = false"></span>
                    <span ng-show="!launcher.showCredentials" class="glyphicon glyphicon-chevron-down" ng-click="launcher.showCredentials = true"></span>
                </div>
                <h3 class="panel-title">AWS credentials</h3>
            </div>
            <div class="panel-body" ng-hide="!launcher.showCredentials">
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
                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-primary" ng-disabled="launcher.launching">Launch Instance</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>