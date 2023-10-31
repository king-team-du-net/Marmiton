<?php

namespace App\Security\Voter;

use App\Entity\User\Editor;
use App\Entity\User\Leader;
use App\Entity\User\Writer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TeamVoter extends Voter
{
    public const ROLE_SUSPEND = 'suspend';
    public const ROLE_ACTIVE = 'active';
    public const ROLE_UPDATE = 'update';
    public const ROLE_RESET = 'reset';
    public const ROLE_DELETE = 'delete';
    public const ROLE_LOG_AS = 'log_as';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [
                self::ROLE_SUSPEND,
                self::ROLE_ACTIVE,
                self::ROLE_UPDATE,
                self::ROLE_RESET,
                self::ROLE_DELETE,
                self::ROLE_LOG_AS,
            ]) && in_array($subject::class, [
                Writer::class,
                Editor::class,
                Leader::class,
            ]);
    }

    /**
     * @param Editor|Writer|Leader $user
     */
    protected function voteOnAttribute(string $attribute, $user, TokenInterface $token): bool
    {
        /** @var Leader $leader */
        $leader = $token->getUser();

        if ($leader instanceof Leader && !$leader->getMembers()->contains($user->getMember())) {
            return false;
        }

        switch ($attribute) {
            case self::ROLE_ACTIVE:
                return $user->isSuspended();
            case self::ROLE_SUSPEND:
                return !$user->isSuspended();
        }

        return true;
    }
}
