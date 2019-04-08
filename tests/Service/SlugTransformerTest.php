<?php

namespace App\Tests\Service;

use App\Service\SlugTransformer;
use PHPUnit\Framework\TestCase;

class SlugTransformerTest extends TestCase
{


    /**
     * @dataProvider slugsForNotEmptyText
     */
    public function testTextToSlug($entry, $expectedSlug)
    {
        $slugTransformer = new SlugTransformer();
        $this->assertEquals($expectedSlug, $slugTransformer->transform($entry));

    }


    public function slugsForNotEmptyText()
    {
        return [
            ['backflip underflip', 'backflip-underflip'],
            ['slug!?test', 'slugtest'],
            ['', 'n-a']
        ];
    }
}
