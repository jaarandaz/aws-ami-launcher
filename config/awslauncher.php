<?php

return [

    'image_id' => env('AWS_LAUNCHER_IMAGE_ID', 'ami-f354ece4'),
    'instance_type' => env('AWS_LAUNCHER_INSTANCE_TYPE', 't2.nano'),
    'aws_region' => env('AWS_LAUNCHER_REGION', 'us-east-1'),

];
