<?php

namespace App\Security\Voter;

use App\Entity\Trick;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class TrickVoter
 * A user can edit his own trick but not others, Admin can edit all tricks
 * @package App\Security\Voter
 */
class TrickVoter extends Voter
{

    const EDIT = 'edit';
    /**
     * @var Security
     */
    private $security;


    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {

        if (!in_array($attribute, [self::EDIT])) {

            return false;
        }

        if (!$subject instanceof Trick) {

            return false;
        }

        return true;

    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        //if the user is the admin then it can edit whatever he want
        if(in_array(User::ROLE_ADMIN, $user->getRoles())){
            return true;
        }

        /** @var Trick $trick */
        $trick = $subject;

        return $trick->getUser() === $user;


    }
}
