<?php

declare(strict_types=1);

namespace App\Security\Exception;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class AccountSuspendedException extends CustomUserMessageAuthenticationException
{
    public function __construct(
        string $message = 'Your account has been suspended',
        array $messageData = [],
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $messageData, $code, $previous);
    }
}
