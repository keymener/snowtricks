<?php

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{

    public function testLogout()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testForgot()
    {
        $client = static::createClient();
        $client->request('GET', '/forgot_password');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }


    public function testRegister()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}
