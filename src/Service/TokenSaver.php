<?php
/**
 * Created by PhpStorm.
 * User: keyme
 * Date: 18/03/2019
 * Time: 21:39
 */

namespace App\Service;


use App\Entity\Token;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class TokenSaver
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * Persist into database new token object
     *
     * @param User $user
     * @param string $tokenValue
     * @throws \Exception
     */
    public function save(User $user, string $tokenValue)
    {

        $token = $this->entityManager->getRepository(Token::class)->findOneBy([
                'user' => $user
            ]
        );

        if (null === $token) {
            $token = new Token();
        }


        //add lifetime to current date
        $dateTime = new \DateTime();
        $dateTime->add(new \DateInterval('PT' . $token::LIFETIME . 'M'));

        $token->setEndDate($dateTime);
        $token->setUser($user);
        $token->setValue($tokenValue);


        if (!$this->entityManager->contains($token)) {
            $this->entityManager->persist($token);
        }
        $this->entityManager->flush();

    }

}
