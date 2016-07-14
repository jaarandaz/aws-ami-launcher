<?php
namespace App\Services;

use App\Models\Ec2Instance;
use App\Models\Ec2InstanceStatus;

use App\Models\AwsLauncherResponse;

use AWS;
use Log;
use Carbon\Carbon;

class AwsLauncherService {

	const EC2_SECURITY_GROUP = "AwsLauncherSecurityGroup";
	const EC2_SECURITY_GROUP_DESCRIPTION = "Aws Launcher Security Group";

    public function launchInstance($credentials) {
        $ec2Client = $this->initEc2Client($credentials);
        $securityGroupId = $this->createSecurityGroup($ec2Client);

        try {

            $instanceData = $this->launchInstanceCall($ec2Client, $securityGroupId);

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

    public function instanceStatus($credentials, $instanceId){
        $ec2Client = $this->initEc2Client($credentials);

        try {
            $result = $ec2Client->describeInstanceStatus([
                	'InstanceIds'   => [$instanceId]
            	]);

            $instanceStatusData = $result['InstanceStatuses'][0];

            return new AwsLauncherResponse([
            		'status'            => AwsLauncherResponse::STATUS_OK,
            		'ec2InstanceStatus' => $this->ec2StatusFromData($instanceStatusData)]);

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

    private function createSecurityGroup($ec2Client) {
    	$timestamp = Carbon::now()->timestamp;
    	$securityGroupName = self::EC2_SECURITY_GROUP.$timestamp;
		
		$result = $ec2Client->createSecurityGroup(array(
			'GroupName'   => $securityGroupName,
			'Description' => 'AWS Launcher security group'
		));

		$securityGroupId = $result['GroupId'];

        $this->openSecurityGroupPorts($ec2Client, $securityGroupId);

		return $securityGroupId;
    }

    private function openSecurityGroupPorts($ec2Client, $securityGroupId) {
        $result = $ec2Client->authorizeSecurityGroupIngress([
        		'GroupId' => $securityGroupId,
        		'IpPermissions' => [
					[	
						'IpProtocol' => 'tcp',
					 	'FromPort'   => 80,
					 	'ToPort'     => 80,
					 	'IpRanges'   => [
							['CidrIp' => '0.0.0.0/0']
					  	]
					],
					[	
						'IpProtocol' => 'tcp',
						'FromPort'   => 443,
						'ToPort'     => 443,
						'IpRanges'   => [
							['CidrIp' => '0.0.0.0/0']
						]
					]
				]]);
    }

    private function launchInstanceCall($ec2Client, $securityGroupId) {
    	$result = $ec2Client->runInstances(array(
                'ImageId'        => config('awslauncher.image_id'),
                'MinCount'       => 1,
                'MaxCount'       => 1,
                'InstanceType'   => config('awslauncher.instance_type'),
                'SecurityGroupsIds' => [$securityGroupId]
            ));

    	return $result['Instances'][0];
    }

    private function ec2InstanceFromData($instanceData) {
    	$instanceStatus = new Ec2InstanceStatus([
            	'name'           => $instanceData['State']['Name']]);

    	return new Ec2Instance([
                    'instanceId'    => $instanceData['InstanceId'],
                    'imageId'       => $instanceData['ImageId'],
                    'publicDnsName' => $instanceData['PublicDnsName'],
                    'publicIp'		=> isset($instanceData['PublicIpAddress'])?
                    		$instanceData['PublicIpAddress'] : "",
                    'instanceType'  => $instanceData['InstanceType'],
                    'region'        => $instanceData['Placement']['AvailabilityZone'],
                    'status'        => $instanceStatus
                ]);
    }

    private function ec2StatusFromData($statusData) {
    	return new Ec2InstanceStatus([
            	'name'           => $statusData['InstanceState']['Name'],
            	'instanceStatus' => $statusData['SystemStatus']['Status'],
            	'systemStatus'   => $statusData['InstanceStatus']['Status']]);
    }
}