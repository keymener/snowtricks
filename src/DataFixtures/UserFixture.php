<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture implements OrderedFixtureInterface
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setActive(true)
            ->setUsername('userTest')
            ->setRoles(['ROLE_USER'])
            ->setEmail('test@myemail.com');

        $password = $this->passwordEncoder->encodePassword($user, 'test');

        $user->setPassword($password);
        $manager->persist($user);
        $manager->flush();


        $this->addReference('user', $user);

    }

    public function getOrder()
    {
        return 1;
    }
}
