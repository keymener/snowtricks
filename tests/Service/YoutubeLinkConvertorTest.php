<?php

namespace App\Tests\Service;

use App\Service\YoutubeLinkConvertor;
use PHPUnit\Framework\TestCase;

class YoutubeLinkConvertorTest extends TestCase
{
    public function testConvertYoutubeUrl()
    {
        $youtubeLinkConvertor = new YoutubeLinkConvertor();

        $convertedUrl = $youtubeLinkConvertor->convert('https://www.youtube.com/watch?v=SQyTWk7OxSI');

        $this->assertSame('https://www.youtube.com/embed/SQyTWk7OxSI', $convertedUrl);
    }

    public function testNonYoutubeUrl()
    {
        $youtubeLinkConvertor = new YoutubeLinkConvertor();


        $convertedUrl = $youtubeLinkConvertor->convert('https://www.test-url.com/test');



        $this->assertSame('https://www.test-url.com/test', $convertedUrl);
    }
}
