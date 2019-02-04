<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Entity\Video;
use App\Service\DefaultImageSelector;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class TrickFixture extends Fixture
{

    /**
     * @var DefaultImageSelector
     */
    private $defaultImageSelector;

    public function __construct(DefaultImageSelector $defaultImageSelector)
    {

        $this->defaultImageSelector = $defaultImageSelector;
    }


    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 0; $i < 5; $i++) {

            $trick = new Trick();
            $trick->setName('Figure' . $i);
            $trick->setDate(new \DateTime());

            $trick->setDescription('
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
             dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
              ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu 
              fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt
               mollit anim id est laborum.
            
            ');


            $group = new TrickGroup();
            $group->setName('Mon groupe ' . $i);


            $video = new Video();
            $video->setUrl('https://www.youtube.com/embed/W853WVF5AqI');


            $trick->setFirstImage($this->defaultImageSelector->getDefaultImage());

            $trick->addVideo($video);
            $trick->setTrickGroup($group);

            $manager->persist($group);


            $manager->persist($video);
            $manager->persist($trick);
        }


        $manager->flush();
    }


}
