<?php

namespace App\Entity;

/**
 * Contains all roles started in the HasRoles.
 */
final class HasRoles
{
    // Role leader (Administrator)
    public const LEADER = 'ROLE_LEADER';

    // Role Editor publisher the book
    public const EDITOR = 'ROLE_EDITOR';

    // Role Writer author the book
    public const WRITER = 'ROLE_WRITER';

    // Role User
    public const CUSTOMER = 'ROLE_CUSTOMER';


    // Role SuperAdmin
    public const ADMINISTRATOR = 'ROLE_ADMINISTRATOR';

    // Role Admin
    public const ADMIN = 'ROLE_ADMIN';

    // Role Book
    public const BOOK = 'ROLE_BOOK_ADMIN';

    // Role Moderator editor the article
    public const MODERATOR = 'ROLE_MODERATOR';

    // Role User
    public const DEFAULT = 'ROLE_USER';
}
