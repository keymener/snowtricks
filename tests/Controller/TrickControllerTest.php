<?php

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickControllerTest extends WebTestCase
{
    public function testHomepageIsOk()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testMorePageIsOk()
    {

        $client = static::createClient();
        $client->request('GET', '/view-5');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }


    public function testNewPageIsOk()
    {

        $client = static::createClient();
        $client->request('GET', '/member/trick/new');

        $crawler = $client->followRedirect();

        $this->assertSame('Authentification' , $crawler->filterXPath('//h1')->text());
    }


}
