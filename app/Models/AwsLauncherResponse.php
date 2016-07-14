<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AwsLauncherResponse extends Model {

	protected $fillable = ['status', 'ec2Instance', 'errorMessage', 'ec2InstanceStatus'];

	const STATUS_OK = "ok";
	const STATUS_ERROR = "error";

	public function isOk() {
		return ($this->status == self::STATUS_OK);
	}

	public function isError() {
		return ($this->status == self::STATUS_ERROR);
	}
}
