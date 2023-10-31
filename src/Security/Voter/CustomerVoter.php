<?php

namespace App\Security\Voter;

use App\Entity\User\Customer;
use App\Entity\User\Editor;
use App\Entity\User\Leader;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CustomerVoter extends Voter
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
            ]) && $subject instanceof Customer;
    }

    /**
     * @param Customer $customer
     */
    protected function voteOnAttribute(string $attribute, $customer, TokenInterface $token): bool
    {
        /** @var Editor|Leader $team */
        $team = $token->getUser();

        if (!$this->voteByRoles($customer, $team)) {
            return false;
        }

        if (in_array($attribute, [self::ROLE_RESET, self::ROLE_LOG_AS, self::ROLE_UPDATE])) {
            return true;
        }

        if (self::ROLE_ACTIVE === $attribute) {
            return $this->voteOnActive($customer, $team);
        }

        if (self::ROLE_SUSPEND === $attribute) {
            return $this->voteOnSuspend($customer, $team);
        }

        return $team instanceof Leader;
    }

    private function voteByRoles(Customer $customer, Editor|Leader $team): bool
    {
        if ($team instanceof Leader) {
            return $team->getMembers()->contains($customer->getClient()->getMember());
        }

        return $customer->getClient()->getEditor() === $team;
    }

    private function voteOnActive(Customer $customer, Editor|Leader $team): bool
    {
        return $customer->isSuspended() && $team instanceof Leader;
    }

    private function voteOnSuspend(Customer $customer, Editor|Leader $team): bool
    {
        return !$customer->isSuspended() && $team instanceof Leader;
    }
}
