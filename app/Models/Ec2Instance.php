<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ec2Instance extends Model {

    protected $fillable = [
            'instanceId' ,
            'imageId',
            'securityGroupId',
            'publicDnsName',
            'publicIp',
            'instanceType',
            'region',
            'status'
        ];

}
