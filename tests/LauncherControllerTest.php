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
        $fakeCredentials = [];

        $this->json('POST', '/launchAmi', $noCredentials)
             ->seeJson([
                 'error' => true,
             ]);
    }

    public function testWrongCredentials() {
        $fakeCredentials = ['accesKey' => 'fakeAccessKey'
				'secretKey' => 'fakeSecretKey'];

        $this->json('POST', '/launchAmi', $fakeCredentials)
             ->seeJson([
                 'error' => true,
             ]);
    }

}
