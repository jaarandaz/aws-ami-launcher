<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class LauncherController extends Controller {
    
    public function index() {
        return view('welcome');
    }


    public function launchAmi(Request $request){
    	$this->validate($request, [
                'accessKey'	=> 'required | string | max:255',
                'secretKey' => 'required | string | max:255']);
    }
}