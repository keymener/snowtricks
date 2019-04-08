<?php

namespace App\Tests\Service;


use App\Service\UrlMaker;
use PHPUnit\Framework\TestCase;


class UrlMakerTest extends TestCase
{

    public function testWithoutInterfaceIntoConstructor()
    {
        $this->expectException('TypeError');
        new UrlMaker(\stdClass::class);
    }


    public function testGenerate()
    {
        $urlGeneratorInterface = $this->getMockBuilder('Symfony\Component\Routing\Generator\UrlGeneratorInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $urlMaker = new UrlMaker($urlGeneratorInterface);

        $this->assertEquals('test', $urlMaker->generate('security_confirm_registration', 'my_token'));

    }


}
