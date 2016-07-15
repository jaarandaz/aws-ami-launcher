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
            
            @include('_credentialsPanel')

            @include('_serverInfoPanel')
            
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
                        instanceStatus : "{{route('launcher.instanceStatus')}}",
                        terminateInstance : "{{route('launcher.terminateInstance')}}"
                    }};
        </script>
    </body>
</html>