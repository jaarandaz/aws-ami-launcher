<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LauncherControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHome() {
        $this->visit('/')
            ->see('AWS AMI Laucher')
            ->dontSee('Laravel 5');
    }

    public function testNoCredentials() {
        $noCredentials = [];

        $this->json('POST', '/launchAmi', $noCredentials)
             ->seeJsonEquals([
                'accessKey' => ['The access key field is required.'],
				'secretKey' => ['The secret key field is required.']
             ])
             ->assertResponseStatus(422);
    }

    public function testWrongCredentials() {
        $fakeCredentials = ['accessKey' => 'fakeAccessKey',
				'secretKey' => 'fakeSecretKey'];

        $this->json('POST', '/launchAmi', $fakeCredentials)
             ->seeJson([
                 'error' => true,
             ]);
    }

}
