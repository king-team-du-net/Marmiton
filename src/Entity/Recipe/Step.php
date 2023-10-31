<?php

namespace App\Entity\Recipe;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\HasIdTrait;
use App\Entity\Traits\HasPriorityTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Repository\Recipe\StepRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StepRepository::class)]
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
class Step
{
    use HasIdTrait;
    use HasPriorityTrait;
    use HasTimestampTrait;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['get'])]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'steps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    /**
     * @var Collection<int, Thumbnail>
     */
    #[ORM\OneToMany(mappedBy: 'step', targetEntity: Thumbnail::class, cascade: ['persist', 'remove'])]
    #[Groups(['get'])]
    private Collection $thumbnails;

    public function __construct()
    {
        $this->thumbnails = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getRecipe().' n°'.$this->getPriority();
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

    public function getExcerpts(): string
    {
        if (null === $this->content) {
            return '';
        }

        $parts = preg_split("/(\r\n|\r|\n){2}/", $this->content);

        return false === $parts ? '' : strip_tags($parts[0]);
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

    /**
     * @return Collection<int, Thumbnail>
     */
    public function getThumbnails(): Collection
    {
        return $this->thumbnails;
    }

    public function addThumbnail(Thumbnail $thumbnail): static
    {
        if (!$this->thumbnails->contains($thumbnail)) {
            $this->thumbnails->add($thumbnail);
            $thumbnail->setStep($this);
        }

        return $this;
    }

    public function removeThumbnail(Thumbnail $thumbnail): static
    {
        if ($this->thumbnails->removeElement($thumbnail)) {
            // set the owning side to null (unless already changed)
            if ($thumbnail->getStep() === $this) {
                $thumbnail->setStep(null);
            }
        }

        return $this;
    }
}
