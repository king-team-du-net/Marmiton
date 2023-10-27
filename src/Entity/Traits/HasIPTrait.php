<?php

/*
 * @package Symfony Framework
 *
 * @author App Freshcart E-commerce <robertdequidt@gmail.com>
 *
 * @copyright 2020-2023
 */

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait HasIPTrait
{
    #[ORM\Column(type: Types::STRING, length: 46, nullable: true)]
    private ?string $ip = null;

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): static
    {
        $this->ip = $ip;

        return $this;
    }
}
