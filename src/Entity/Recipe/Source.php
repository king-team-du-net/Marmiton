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
use App\Repository\Recipe\SourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SourceRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Patch(security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_USER')"),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_USER')"),
    ],
    normalizationContext: ['groups' => ['get']]
)]
class Source
{
    use HasIdNameSlugAssertTrait;
    use HasContentTrait;
    use HasTimestampTrait;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    #[Groups(['get'])]
    private ?string $url = null;

    /**
     * @var Collection<int, RecipeHasSource>
     */
    #[ORM\OneToMany(mappedBy: 'source', targetEntity: RecipeHasSource::class, orphanRemoval: true)]
    private Collection $recipeHasSources;

    public function __toString(): string
    {
        return $this->getName().' ('.$this->getId().')';
    }

    public function __construct()
    {
        $this->recipeHasSources = new ArrayCollection();
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection<int, RecipeHasSource>
     */
    public function getRecipeHasSources(): Collection
    {
        return $this->recipeHasSources;
    }

    public function addRecipeHasSource(RecipeHasSource $recipeHasSource): static
    {
        if (!$this->recipeHasSources->contains($recipeHasSource)) {
            $this->recipeHasSources->add($recipeHasSource);
            $recipeHasSource->setSource($this);
        }

        return $this;
    }

    public function removeRecipeHasSource(RecipeHasSource $recipeHasSource): static
    {
        if ($this->recipeHasSources->removeElement($recipeHasSource)) {
            // set the owning side to null (unless already changed)
            if ($recipeHasSource->getSource() === $this) {
                $recipeHasSource->setSource(null);
            }
        }

        return $this;
    }
}
