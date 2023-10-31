<?php

namespace App\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait HasBadgeCategoryTrait
{
    use HasIdNameSlugAssertTrait;
    use HasTimestampTrait;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function __toString(): string
    {
        return $this->getName().' ('.$this->getId().')';
    }

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
