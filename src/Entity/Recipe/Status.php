<?php

namespace App\Entity\Recipe;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Recipe\UserRecipe;
use App\Entity\Traits\HasIdNameTrait;
use App\Repository\Recipe\StatusRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    use HasIdNameTrait;

    /**
     * @var Collection<int, UserRecipe>
     */
    #[ORM\OneToMany(mappedBy: 'status', targetEntity: UserRecipe::class)]
    private Collection $userRecipes;

    public function __construct()
    {
        $this->userRecipes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return Collection<int, UserRecipe>
     */
    public function getUserRecipes(): Collection
    {
        return $this->userRecipes;
    }

    public function addUserRecipe(UserRecipe $userRecipe): static
    {
        if (!$this->userRecipes->contains($userRecipe)) {
            $this->userRecipes->add($userRecipe);
            $userRecipe->setStatus($this);
        }

        return $this;
    }

    public function removeUserRecipe(UserRecipe $userRecipe): static
    {
        if ($this->userRecipes->removeElement($userRecipe)) {
            // set the owning side to null (unless already changed)
            if ($userRecipe->getStatus() === $this) {
                $userRecipe->setStatus(null);
            }
        }

        return $this;
    }
}
