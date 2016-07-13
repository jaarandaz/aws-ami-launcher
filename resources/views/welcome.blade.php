<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/lib/bootstrap.css')}}">
        {!!Html::script('js/lib/jquery.js')!!}
        {!!Html::script('js/lib/bootstrap.js')!!}
        {!!Html::script('js/lib/angular.js')!!}
        {!!Html::script('js/app.js')!!}
        <title>Launcher</title>
    </head>
    <body>
        <div class="container">
            <div class="page-header">
                <h1>AWS AMI Laucher</h1>
            </div>
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Insert your AWS credentials</h3>
                        </div>
                        <div class="panel-body">
                            {!! Form::open(['route' => ['launcher.launchAmi'], 'method' => 'post', 'class' => 'form-horizontal']) !!}
                                <div class="form-group">
                                    <label for="accesKey" class="col-sm-3 control-label">Access Key ID</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="accesKey" placeholder="AWS Access Key" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="secretKey" class="col-sm-3 control-label">Secret Key</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="secretKey" placeholder="Secret Access Key" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary">Launch!</button>
                                    </div>
                                </div>
                            {!! Form::close() !!}
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
    </body>
</html>