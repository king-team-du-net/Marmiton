<?php

namespace App\Entity\Recipe;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\HasContentTrait;
use App\Entity\Traits\HasIdNameSlugAssertTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Repository\Recipe\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Patch(),
        new Delete(),
        new GetCollection(),
        new Post(),
    ],
    normalizationContext: ['groups' => ['get']],
    security: "is_granted('ROLE_USER')"
)]
class Ingredient
{
    use HasIdNameSlugAssertTrait;
    use HasContentTrait;
    use HasTimestampTrait;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0])]
    #[Groups(['get'])]
    #[Assert\NotNull]
    private ?bool $glutenFree = false;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0])]
    #[Groups(['get'])]
    #[Assert\NotNull]
    private ?bool $dairyFree = false;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 1])]
    #[Groups(['get'])]
    #[Assert\NotNull]
    private ?bool $vegetarian = true;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0])]
    #[Groups(['get'])]
    #[Assert\NotNull]
    private ?bool $vegan = false;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: RecipeHasIngredient::class, orphanRemoval: true)]
    private Collection $recipeHasIngredients;

    public function __construct()
    {
        $this->recipeHasIngredients = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName().' ('.$this->getId().')';
    }

    public function isGlutenFree(): ?bool
    {
        return $this->glutenFree;
    }

    public function setGlutenFree(bool $glutenFree): static
    {
        $this->glutenFree = $glutenFree;

        return $this;
    }

    public function isDairyFree(): ?bool
    {
        return $this->dairyFree;
    }

    public function setDairyFree(bool $dairyFree): static
    {
        $this->dairyFree = $dairyFree;

        return $this;
    }

    public function isVegetarian(): ?bool
    {
        return $this->vegetarian;
    }

    public function setVegetarian(bool $vegetarian): static
    {
        $this->vegetarian = $vegetarian;

        return $this;
    }

    public function isVegan(): ?bool
    {
        return $this->vegan;
    }

    public function setVegan(bool $vegan): static
    {
        $this->vegan = $vegan;

        return $this;
    }

    /**
     * @return Collection<int, RecipeHasIngredient>
     */
    public function getRecipeHasIngredients(): Collection
    {
        return $this->recipeHasIngredients;
    }

    public function addRecipeHasIngredient(RecipeHasIngredient $recipeHasIngredient): static
    {
        if (!$this->recipeHasIngredients->contains($recipeHasIngredient)) {
            $this->recipeHasIngredients->add($recipeHasIngredient);
            $recipeHasIngredient->setIngredient($this);
        }

        return $this;
    }

    public function removeRecipeHasIngredient(RecipeHasIngredient $recipeHasIngredient): static
    {
        if ($this->recipeHasIngredients->removeElement($recipeHasIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeHasIngredient->getIngredient() === $this) {
                $recipeHasIngredient->setIngredient(null);
            }
        }

        return $this;
    }
}
