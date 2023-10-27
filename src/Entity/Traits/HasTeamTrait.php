<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\String\u;

trait HasTeamTrait
{
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $about = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $designation = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0])]
    #[Assert\NotNull]
    private bool $team = false;

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): static
    {
        $this->about = $about;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return u($this->designation)->upper()->toString();
    }

    public function setDesignation(?string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function isTeam(): bool
    {
        return $this->team;
    }

    public function getTeam(): bool
    {
        return $this->team;
    }

    public function setTeam(bool $team): static
    {
        $this->team = $team;

        return $this;
    }
}
