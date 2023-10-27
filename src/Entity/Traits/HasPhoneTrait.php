<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait HasPhoneTrait
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 20)]
    #[Assert\Regex(
        pattern: '/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/',
        message: 'Invalid phone number.',
        )
    ]
    #[ORM\Column(type: Types::STRING)]
    private string $phone = '';

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone ?: '';

        return $this;
    }
}