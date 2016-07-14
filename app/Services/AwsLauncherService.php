<?php
namespace App\Services;

use App\Models\Ec2Instance;
use App\Models\AwsLauncherResponse;

use AWS;
use Log;

class AwsLauncherService {

    public function launchInstance($credentials) {
        $ec2Client = $this->initEc2Client($credentials);

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

    public function instanceStatus($credentials, $instanceId){
        $ec2Client = $this->initEc2Client($credentials);

        try {
            $result = $ec2Client->describeInstances([
                	'InstanceIds'   => [$instanceId]
            	]);

            $instanceData = $result['Reservations'][0]['Instances'][0];

            Log::error($result);
            Log::error($result['Reservations']);

            return new AwsLauncherResponse([
            		'status'      => AwsLauncherResponse::STATUS_OK,
            		'ec2Instance' => $this->ec2InstanceFromData($instanceData)]);

        } catch(\Exception $e) {
        	Log::error($e->getMessage());

            if (($e->getStatusCode() == "401") && 
                    ($e->getAwsErrorType() == "client") &&
                    ($e->getAwsErrorCode() == "AuthFailure")) {
            	return new AwsLauncherResponse([
            			'status'       => AwsLauncherResponse::STATUS_ERROR,
            			'errorMessage' => 'AWS was not able to validate the provided access credentials']);
            }
            
        }
    }

    private function initEc2Client($credentials) {
    	$credentials = ['credentials' => [
                'key'    => $credentials['accessKey'],
                'secret' => $credentials['secretKey']]];

        return AWS::createClient('Ec2', $credentials);
    }

    private function ec2InstanceFromData($instanceData) {
    	return new Ec2Instance([
                    'instanceId'    => $instanceData['InstanceId'],
                    'imageId'       => $instanceData['ImageId'],
                    'publicDnsName' => $instanceData['PublicDnsName'],
                    'publicIp'		=> $instanceData['PublicIpAddress'],
                    'instanceType'  => $instanceData['InstanceType'],
                    'region'        => $instanceData['Placement']['AvailabilityZone'],
                    'statusCode'    => $instanceData['State']['Code'],
                    'statusName'    => $instanceData['State']['Name'],
                ]);
    }

}