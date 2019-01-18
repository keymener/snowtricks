<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TrickFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 0; $i < 30; $i++) {

            $trick = new Trick();
            $trick->setName('Figure' . $i);
            $trick->setDate(new \DateTime());


            $image = new Image();
            $image->setUrl('https://via.placeholder.com/15' . $i);
            $image->setAlt('Image' . $i);

            $video = new Video();
            $video->setUrl('https://www.youtube.com/watch?v=W853WVF5AqI');

            $trick->addImage($image);
            $trick->addVideo($video);

            $manager->persist($image);
            $manager->persist($video);
            $manager->persist($trick);
        }


        $manager->flush();
    }
}
