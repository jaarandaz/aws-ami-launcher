<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

abstract class AwsLauncherFacade extends Facade
{
    protected static function getFacadeAccessor() { return 'awslauncher'; }
}