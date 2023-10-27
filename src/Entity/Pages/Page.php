<?php

namespace App\Entity\Pages;

use App\Entity\Traits\HasIdTitleSlugAssertTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Entity\Traits\HasViewsTrait;
use App\Repository\Pages\PageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page
{
    use HasIdTitleSlugAssertTrait;
    use HasViewsTrait;
    use HasTimestampTrait;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 100)]
    private ?string $content = null;

    public function __toString(): string
    {
        return (string) $this->getTitle() ?: '';
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }
}
