<?php

// declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Security\Exception\AccountSuspendedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Blocks user authentication.
 */
class UserChecker implements UserCheckerInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * Check that the user has the right to connect.
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return; // @codeCoverageIgnore
        }

        if ($user instanceof User && $user->isSuspended()) {
            throw new AccountSuspendedException($user, [$this->translator->trans('Your account has been suspended.')]);
        }
    }

    /**
     * Verify that the logged in user has permission to continue.
     */
    public function checkPostAuth(UserInterface $user): void
    {
    }
}
