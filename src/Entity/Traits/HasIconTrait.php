<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait HasIconTrait
{
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $icon = null;

    public function displayIcon(): string
    {
        return '<i class="'.$this->icon.'"></i>';
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }
}
