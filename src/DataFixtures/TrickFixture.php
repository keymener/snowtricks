<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

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

            $group = new TrickGroup();
            $group->setName('Mon groupe ' . $i);

            $image = new Image();
            $image->setUrl('https://via.placeholder.com/15' . $i);
            $image->setAlt('Image' . $i);

            $video = new Video();
            $video->setUrl('https://www.youtube.com/watch?v=W853WVF5AqI');

            $trick->addImage($image);
            $trick->addVideo($video);
            $trick->setTrickGroup($group);

            $manager->persist($group);
            $manager->persist($image);
            $manager->persist($video);
            $manager->persist($trick);
        }


        $manager->flush();
    }
}
