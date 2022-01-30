<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class HomeVoter extends Voter
{
    const ANONYMOUS = 'anonymous';

    public function __construct(private Security $security)
    {
        
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::ANONYMOUS]))
        {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return !$this->security->getUser();
    }
}
