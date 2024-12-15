<?php

namespace App\Security\Voter;

use App\Entity\Character;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])
            && $subject instanceof Character;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Character $character */
        $character = $subject;

        // Les administrateurs peuvent tout faire
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return match($attribute) {
            self::VIEW, self::EDIT, self::DELETE => $this->isOwner($character, $user),
            default => false,
        };
    }

    private function isOwner(Character $character, UserInterface $user): bool
    {
        /** @var User $user */
        return $character->getUser() === $user;
    }
}