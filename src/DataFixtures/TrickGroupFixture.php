<?php

namespace App\DataFixtures;

use App\Entity\TrickGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TrickGroupFixture extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $grabs = new TrickGroup();
        $grabs->setName('grabs');
        $manager->persist($grabs);

        $rotations = new TrickGroup();
        $rotations->setName('rotations');
        $manager->persist($rotations);

        $flips = new TrickGroup();
        $flips->setName('flips');
        $manager->persist($flips);

        $desaxed = new TrickGroup();
        $desaxed->setName('rotations désaxées');
        $manager->persist($desaxed);

        $slides = new TrickGroup();
        $slides->setName('slides');
        $manager->persist($slides);


        $manager->flush();


        $this->setReference('group-grabs', $grabs);
        $this->setReference('group-rotations', $rotations);
        $this->setReference('group-flips', $flips);
        $this->setReference('group-desaxed', $desaxed);
        $this->setReference('group-slides', $slides);
    }


    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}
