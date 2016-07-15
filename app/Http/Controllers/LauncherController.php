<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Services\AwsLauncherService;

use Validator;

use Log;

class LauncherController extends Controller {

    const JSON_TO_ARRAY = true;
    const HTTP_UNPROCESABLE_ENTITY_CODE = 422;

    public function __construct(AwsLauncherService $awsLauncher) {
        $this->awsLauncherService = $awsLauncher;
    }

    public function index() {
        return view('welcome');
    }

    public function launchAmi(Request $request){
        $validator = $this->validateCredentialsNotEmpty($request);
        if ($validator->fails()) {
            return response($validator->messages()->toJson(), self::HTTP_UNPROCESABLE_ENTITY_CODE);
        }

        $awsLauncherResponse = $this->awsLauncherService->
                launchInstance($request['credentials']);

        if ($awsLauncherResponse->isOk()) {
            return response()->json($awsLauncherResponse->ec2Instance);
        } else {
            return response()->json($this->awsValidationError($awsLauncherResponse), self::HTTP_UNPROCESABLE_ENTITY_CODE);
        }
    }

    public function instance(Request $request) {
        $validator = $this->validateCredentialsAndInstanceNotEmptyJson($request);
        if ($validator->fails()) {
            return response($validator->messages()->toJson(), self::HTTP_UNPROCESABLE_ENTITY_CODE);
        }

        $awsLauncherResponse = $this->awsLauncherService
                ->getInstance(json_decode($request['credentials'], self::JSON_TO_ARRAY), $request['instanceId']);

        if ($awsLauncherResponse->isOk()) {
            return response()->json($awsLauncherResponse->ec2Instance);
        } else {
            return response()->json($this->awsValidationError($awsLauncherResponse), self::HTTP_UNPROCESABLE_ENTITY_CODE);
        }
    }

    public function instanceStatus(Request $request){
        $validator = $this->validateCredentialsAndInstanceNotEmptyJson($request);
        if ($validator->fails()) {
            return response($validator->messages()->toJson(), self::HTTP_UNPROCESABLE_ENTITY_CODE);
        }

        $awsLauncherResponse = $this->awsLauncherService
                ->instanceStatus(json_decode($request['credentials'], self::JSON_TO_ARRAY), $request['instanceId']);

        if ($awsLauncherResponse->isOk()) {
            return response()->json($awsLauncherResponse->ec2InstanceStatus);
        } else {
            return response()->json($this->awsValidationError($awsLauncherResponse), self::HTTP_UNPROCESABLE_ENTITY_CODE);
        }
    }

    public function terminateInstance(Request $request) {
        $validator = $this->validateCredentialsAndInstanceNotEmpty($request);
        if ($validator->fails()) {
            return response($validator->messages()->toJson(), self::HTTP_UNPROCESABLE_ENTITY_CODE);
        }

        $awsLauncherResponse = $this->awsLauncherService
                ->terminateInstance($request['credentials'], $request['instanceId']);

        if ($awsLauncherResponse->isOk()) {
            return response()->json($awsLauncherResponse->ec2InstanceStatus);
        } else {
            return response()->json($this->awsValidationError($awsLauncherResponse), self::HTTP_UNPROCESABLE_ENTITY_CODE);
        }
    }

    private function validateCredentialsAndInstanceNotEmptyJson($request) {
        $parameters = json_decode($request['credentials'], self::JSON_TO_ARRAY);
        $parameters['instanceId'] = $request['instanceId'];

        return Validator::make($parameters, [
                'accessKey' => 'required | string | max:255',
                'secretKey' => 'required | string | max:255',
                'instanceId' => 'required | string | max:255']);
    }

    private function validateCredentialsNotEmpty($request) {
        $credentials = $request['credentials'];

        return Validator::make($credentials, [
                'accessKey'	=> 'required | string | max:255',
                'secretKey' => 'required | string | max:255']);
    }

    private function awsValidationError($awsLauncherResponse) {
        return [
                'authentication' => [$awsLauncherResponse->errorMessage]
            ];
    }

    private function validateCredentialsAndInstanceNotEmpty($request) {
        $parameters = $request['credentials'];
        $parameters['instanceId'] = $request['instanceId'];       

        return Validator::make($parameters, [
                'accessKey'       => 'required | string | max:255',
                'secretKey'       => 'required | string | max:255',
                'instanceId'      => 'required | string | max:255']);
    }
}