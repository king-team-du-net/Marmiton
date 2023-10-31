<?php

namespace App\Service;

/**
 * Generates a random string of a defined size.
 *
 * @author App Freshcart E-commerce <robertdequidt@gmail.com>
 */
final class GeneratorTokenService
{
    /**
     * Generates a CSRF token.
     */
    public function generateToken(int $length = 25): string
    {
        $length = max(2, min(\PHP_INT_MAX, $length));
        /** @var int<1, max> $secondLength */
        $secondLength = (int) ceil($length / 2);

        return mb_substr(bin2hex(random_bytes($secondLength)), 0, $length);
    }
}
