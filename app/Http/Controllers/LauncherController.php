<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Validator;

class LauncherController extends Controller {

    public function index() {
        return view('welcome');
    }


    public function launchAmi(Request $request){
    	$validator = $this->validateCredentialsNotEmpty($request->only('credentials'));
    	if ($validator->fails()) {

    		return response($validator->messages()->toJson(), 422);
    	}
    }

    private function validateCredentialsNotEmpty($credentials) {
        return Validator::make($credentials, [
                'accessKey'	=> 'required | string | max:255',
                'secretKey' => 'required | string | max:255']);
	}
}