<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Ec2Instance;

use Validator;
use AWS;

use Log;

class LauncherController extends Controller {

    public function index() {
        return view('welcome');
    }

    public function launchAmi(Request $request){
        $validator = $this->validateCredentialsNotEmpty($request['credentials']);

        if ($validator->fails()) {
            return response($validator->messages()->toJson(), 422);
        }

        $instanceData = $this->launchAWSAmi($request['credentials']);

        if ($instanceData) {
            $ec2Instance = new Ec2Instance([
                    'instanceId'    => $instanceData['InstanceId'],
                    'imageId'       => $instanceData['ImageId'],
                    'publicDnsName' => $instanceData['PublicDnsName'],
                    'instanceType'  => $instanceData['InstanceType'],
                    'region'        => $instanceData['Placement']['AvailabilityZone'],
                    'stateCode'     => $instanceData['State']['Code'],
                    'stateName'     => $instanceData['State']['Name'],
                ]);

            return response()->json($ec2Instance);
        }
    }

    private function launchAWSAmi($credentials) {
        $credentials = ['credentials' => [
                'key'    => $credentials['accessKey'],
                'secret' => $credentials['secretKey']]];

        $ec2Client= AWS::createClient('Ec2', $credentials);

        try {
            $result = $ec2Client->runInstances(array(
                'ImageId'        => config('awslauncher.image_id'),
                'MinCount'       => 1,
                'MaxCount'       => 1,
                'InstanceType'   => config('awslauncher.instance_type'),
            ));

            $instanceData = $result['Instances'][0];

            return $instanceData;

        } catch(\Exception $e) {
            if (($e->getStatusCode() == "401") && 
                    ($e->getAwsErrorType() == "client") &&
                    ($e->getAwsErrorCode() == "AuthFailure")) {
                Log::error("cocreta");
            }
            Log::error($e->getMessage());
        }
    }

    private function validateCredentialsNotEmpty($credentials) {
        return Validator::make($credentials, [
                'accessKey'	=> 'required | string | max:255',
                'secretKey' => 'required | string | max:255']);
	}

}