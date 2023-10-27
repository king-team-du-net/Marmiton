<?php

namespace App\Entity;

use App\Entity\Recipe\Recipe;
use App\Entity\Traits\HasDeletedAtTrait;
use App\Entity\Traits\HasIdHeadlineAndSlugTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
class Review
{
    use HasIdHeadlineAndSlugTrait;
    use HasTimestampTrait;
    use HasDeletedAtTrait;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\NotBlank]
    private ?int $rating = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 25, max: 500)]
    private ?string $details = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 1])]
    #[Assert\NotNull]
    private bool $visible = true;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    private ?Recipe $recipe = null;

    // #[ORM\ManyToOne(inversedBy: 'reviews')]
    // private ?User $user = null;

    public function __toString(): string
    {
        return (string) $this->headline;
    }

    public function __construct()
    {
        $this->visible = true;
    }

    public function getRatingPercentage(): int|float
    {
        return ($this->rating / 5) * 100;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function getVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): static
    {
        $this->visible = $visible;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }
    /*
        public function getUser(): ?User
        {
            return $this->user;
        }

        public function setUser(?User $user): static
        {
            $this->user = $user;

            return $this;
        }
    */
}
