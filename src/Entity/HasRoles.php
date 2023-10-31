<?php

namespace App\Entity;

/**
 * Contains all roles started in the HasRoles.
 */
final class HasRoles
{
    // Role SuperAdmin
    public const SUPERADMIN = 'ROLE_SUPER_ADMIN';

    // Role Admin
    public const ADMIN = 'ROLE_ADMIN';

    // Role Moderator editor the article
    public const MODERATOR = 'ROLE_MODERATOR';

    // Role User
    public const DEFAULT = 'ROLE_USER';
}
