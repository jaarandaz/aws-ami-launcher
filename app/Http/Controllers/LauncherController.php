<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Services\AwsLauncherService;

use Validator;

use Log;

class LauncherController extends Controller {

    public function __construct(AwsLauncherService $awsLauncher) {
        $this->awsLauncherService = $awsLauncher;
    }

    public function index() {
        return view('welcome');
    }

    public function launchAmi(Request $request){
        $validator = $this->validateCredentialsNotEmpty($request['credentials']);

        if ($validator->fails()) {
            return response($validator->messages()->toJson(), 422);
        }

        $awsLauncherResponse = $this->awsLauncherService->launchInstance($request['credentials']);

        if ($awsLauncherResponse->isOk()) {
            return response()->json($awsLauncherResponse->ec2Instance);
        } else {
            return response()->json($this->awsValidationError($awsLauncherResponse), 422);
        }
    }

    private function validateCredentialsNotEmpty($credentials) {
        return Validator::make($credentials, [
                'accessKey'	=> 'required | string | max:255',
                'secretKey' => 'required | string | max:255']);
	}

    private function awsValidationError($awsLauncherResponse) {
        return [
                'secretKey' => [$awsLauncherResponse->errorMessage]
            ];
    }
}