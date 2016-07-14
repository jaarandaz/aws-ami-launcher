<?php
namespace App\Services;

use App\Models\Ec2Instance;
use App\Models\AwsLauncherResponse;

use AWS;
use Log;

class AwsLauncherService {

    public function launchInstance($credentials) {
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

            return new AwsLauncherResponse([
            		'status'      => AwsLauncherResponse::STATUS_OK,
            		'ec2Instance' => $this->ec2InstanceFromData($instanceData)]);

        } catch(\Exception $e) {
            if (($e->getStatusCode() == "401") && 
                    ($e->getAwsErrorType() == "client") &&
                    ($e->getAwsErrorCode() == "AuthFailure")) {
            	return new AwsLauncherResponse([
            			'status'       => AwsLauncherResponse::STATUS_ERROR,
            			'errorMessage' => 'AWS was not able to validate the provided access credentials']);
            }            
        }
    }

    private function ec2InstanceFromData($instanceData) {
    	$ec2Instance = new Ec2Instance([
                    'instanceId'    => $instanceData['InstanceId'],
                    'imageId'       => $instanceData['ImageId'],
                    'publicDnsName' => $instanceData['PublicDnsName'],
                    'instanceType'  => $instanceData['InstanceType'],
                    'region'        => $instanceData['Placement']['AvailabilityZone'],
                    'stateCode'     => $instanceData['State']['Code'],
                    'stateName'     => $instanceData['State']['Name'],
                ]);
    }

}