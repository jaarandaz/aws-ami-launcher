<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'LauncherController@index');

Route::post('launchAmi', [
		'uses' => 'LauncherController@launchAmi',
		'as'   => 'launcher.launchAmi'
	]);

Route::get('instance', [
		'uses' => 'LauncherController@instance',
		'as'   => 'launcher.instance'
	]);

Route::get('instanceStatus', [
		'uses' => 'LauncherController@instanceStatus',
		'as'   => 'launcher.instanceStatus'
	]);

Route::post('terminateInstance', [
		'uses' => 'LauncherController@terminateInstance',
		'as'   => 'launcher.terminateInstance'
	]);