<?php

namespace App\DataFixtures;

use App\Entity\TrickGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TrickGroupFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 0; $i < 5; $i++) {
            $trickGroup = new TrickGroup();
            $trickGroup->setName('Groupe de figure '.$i);
            $manager->persist($trickGroup);
        }


        $manager->flush();
    }
}
