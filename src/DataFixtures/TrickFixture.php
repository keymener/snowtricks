<?php

namespace App\DataFixtures;


use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class TrickFixture extends Fixture implements OrderedFixtureInterface
{


    public function load(ObjectManager $manager)
    {


        /** @var User $user */
        $user = $this->getReference('user');

        /** @var TrickGroup $group */
        $group = $this->getReference('group-grabs');


        $trick = new Trick();
        $trick->setName('mute')
            ->setDate(new \DateTime())
            ->setDescription('Saisie de la carre frontside de la planche entre les deux pieds avec la main avant')
            ->setTrickGroup($group)
            ->setUser($user);


        $file = $this->handleFile('public/img/samples/mute-air.jpg');

        $image = $this->handleImage('Mute 1', $file);

        $trick->addImage($image);

        $manager->persist($image);


        $file = $this->handleFile('public/img/samples/mute-grab-snowboarding.jpg');

        $image = $this->handleImage('Mute 2', $file);

        $trick->addImage($image);

        $manager->persist($image);


        $video = new Video();
        $video->setUrl('https://www.youtube.com/watch?v=yyN1gQqsMwM');

        $manager->persist($video);

        $trick->addVideo($video);


        $manager->persist($trick);

        $trick = new Trick();
        $trick->setName('indy')
            ->setDate(new \DateTime())
            ->setDescription('Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière')
            ->setTrickGroup($group)
            ->setUser($user);


        $file = $this->handleFile('public/img/samples/indy.jpg');

        $image = $this->handleImage('Indy 1', $file);

        $trick->addImage($image);

        $manager->persist($image);

        $file = $this->handleFile('public/img/samples/indy2.jpg');

        $image = $this->handleImage('Indy 2', $file);

        $trick->addImage($image);

        $manager->persist($image);

        $video = new Video();
        $video->setUrl('https://www.youtube.com/watch?v=iKkhKekZNQ8');

        $trick->addVideo($video);

        $manager->persist($video);

        $manager->persist($trick);

        $trick = new Trick();
        $trick->setName('stalefish')
            ->setDate(new \DateTime())
            ->setDescription('Saisie de la carre backside de la planche entre les deux pieds avec la main arrière')
            ->setTrickGroup($group)
            ->setUser($user);


        $file = $this->handleFile('public/img/samples/stale1.jpg');

        $image = $this->handleImage('Stalefish 1', $file);

        $trick->addImage($image);

        $manager->persist($image);

        $file = $this->handleFile('public/img/samples/stale2.jpg');

        $image = $this->handleImage('Stalefish 2', $file);

        $trick->addImage($image);

        $manager->persist($image);

        $video = new Video();
        $video->setUrl('https://www.youtube.com/watch?v=f9FjhCt_w2U');

        $trick->addVideo($video);

        $manager->persist($video);

        $manager->persist($trick);


        /** @var TrickGroup $group */
        $group = $this->getReference('group-rotations');

        $trick = new Trick();
        $trick->setName('180')
            ->setDate(new \DateTime())
            ->setDescription('Une rotation peut être frontside ou backside : une rotation frontside correspond à une rotation orientée vers la carre backside. Cela peut paraître incohérent mais l\'origine étant que dans un halfpipe ou une rampe de skateboard, une rotation frontside se déclenche naturellement depuis une position frontside (i.e. l\'appui se fait sur la carre frontside), et vice-versa. Ainsi pour un rider qui a une position regular (pied gauche devant), une rotation frontside se fait dans le sens inverse des aiguilles d\'une montre.

Une rotation peut être agrémentée d\'un grab, ce qui rend le saut plus esthétique mais aussi plus difficile car la position tweakée a tendance à déséquilibrer le rideur et désaxer la rotation. De plus, le sens de la rotation a tendance à favoriser un sens de grab plutôt qu\'un autre. Les rotations de plus de trois tours existent mais sont plus rares, d\'abord parce que les modules assez gros pour lancer un tel saut sont rares, et ensuite parce que la vitesse de rotation est tellement élevée qu\'un grab devient difficile, ce qui rend le saut considérablement moins esthétique.

Pour entrer sur une barre de slide, le rideur peut se mettre perpendiculaire à l\'axe de la barre et fera donc un quart de tour en l\'air, modulo 360 degrés — il est possible de faire n tours complets plus un quart de tour.')
            ->setTrickGroup($group)
            ->setUser($user);


        $file = $this->handleFile('public/img/samples/180.jpg');

        $image = $this->handleImage('180', $file);

        $trick->addImage($image);

        $manager->persist($image);


        $video = new Video();
        $video->setUrl('https://www.youtube.com/watch?v=GnYAlEt-s00');

        $trick->addVideo($video);

        $manager->persist($video);


        $manager->persist($trick);

        $trick = new Trick();
        $trick->setName('360')
            ->setDate(new \DateTime())
            ->setDescription('360, trois six pour un tour complet')
            ->setTrickGroup($group)
            ->setUser($user);

        $video = new Video();
        $video->setUrl('https://www.youtube.com/watch?v=JJy39dO_PPE');

        $trick->addVideo($video);

        $manager->persist($video);

        $manager->persist($trick);

        $manager->flush();


    }


    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }

    /**
     * Set the image file to UploadedFile file
     *
     * @param string $sourceImage
     * @return UploadedFile
     */
    public function handleFile(string $sourceImage): UploadedFile
    {
        //create a temp file
        copy($sourceImage, 'public/img/samples/tmp.jpg');

        //this will remove the temp file after instancing UploadedFile
        $file = new UploadedFile('public/img/samples/tmp.jpg', 'tmp.jpg', 'image/jpeg', null, true);

        return $file;
    }

    public function handleImage(string $name, UploadedFile $file)
    {
        $image = new Image();
        $image->setName($name);
        $image->setFile($file);
        $image->setDateUpdate(new \DateTime());

        return $image;

    }
}
