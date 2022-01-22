<?php

namespace App\Security\Voter;

use App\Entity\Card;
use App\Entity\CardsList;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ApiVoter extends Voter
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        $supportsAttribute = in_array($attribute, ['API']);
        $supportsSubject = $subject instanceof Card || $subject instanceof CardsList;

        return $supportsSubject && $supportsAttribute;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $result = false;
        $class = get_class($subject);
        switch ($class) {
            case Card::class:
                $result = $this->security->getUser() == $subject->getCardsList()->getUser();
                break;
            case CardsList::class:
                $result = $this->security->getUser() == $subject->getUser();
                break;
        }
        return $result;
    }
}
