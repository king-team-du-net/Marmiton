<?php

namespace App\Entity\Recipe;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\HasIdTrait;
use App\Repository\Recipe\RecipeHasIngredientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeHasIngredientRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Patch(security: "is_granted('ROLE_ADMIN') or object.getRecipe().getUser() == user"),
        new Delete(security: "is_granted('ROLE_ADMIN') or object.getRecipe().getUser() == user"),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_ADMIN') or object.getRecipe().getUser() == user"),
    ],
    normalizationContext: ['groups' => ['get']]
)]
class RecipeHasIngredient
{
    use HasIdTrait;

    #[ORM\Column(type: Types::FLOAT)]
    #[Groups(['get'])]
    private ?float $quantity = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0])]
    #[Groups(['get'])]
    #[Assert\NotNull]
    private bool $optional = false;

    #[ORM\ManyToOne(inversedBy: 'recipeHasIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    #[ORM\ManyToOne(inversedBy: 'recipeHasIngredients')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Unit $unit = null;

    #[ORM\ManyToOne(inversedBy: 'recipeHasIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get'])]
    private ?Ingredient $ingredient = null;

    #[ORM\ManyToOne(inversedBy: 'recipeHasIngredients')]
    #[Groups(['get'])]
    private ?IngredientGroup $ingredientGroup = null;

    public function __toString(): string
    {
        return $this->getRecipe().' - '.$this->getIngredient();
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function isOptional(): bool
    {
        return $this->optional;
    }

    public function setOptional(bool $optional): static
    {
        $this->optional = $optional;

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

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): static
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getIngredientGroup(): ?IngredientGroup
    {
        return $this->ingredientGroup;
    }

    public function setIngredientGroup(?IngredientGroup $ingredientGroup): static
    {
        $this->ingredientGroup = $ingredientGroup;

        return $this;
    }
}
