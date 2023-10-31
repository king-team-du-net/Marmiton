<?php

namespace App\Entity\Recipe;

use App\Entity\Traits\HasIdTrait;
use App\Entity\Traits\HasRatingTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Entity\User;
use App\Repository\Recipe\UserRecipeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRecipeRepository::class)]
class UserRecipe
{
    use HasIdTrait;
    use HasRatingTrait;
    use HasTimestampTrait;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'userRecipes')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userRecipes')]
    private ?Recipe $recipe = null;

    #[ORM\ManyToOne(inversedBy: 'userRecipes')]
    private ?Status $status = null;

    public function __toString()
    {
        return $this->getRecipe()->getName();
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }
}
