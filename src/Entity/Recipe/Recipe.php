<?php

namespace App\Entity\Recipe;

use App\Entity\User;
use App\Entity\Review;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\HasViewsTrait;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\HasContentTrait;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\HasTimestampTrait;
use App\Entity\Setting\HomepageHeroSetting;
use App\Repository\Recipe\RecipeRepository;
use Doctrine\Common\Collections\Collection;
use App\Entity\Traits\HasIdNameSlugAssertTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: [
            'groups' => ['get', 'Recipe:item:get'],
        ]),
        new Patch(security: "is_granted('ROLE_ADMIN') or object.getUser() == user"),
        new Delete(security: "is_granted('ROLE_ADMIN') or object.getUser() == user"),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_USER')"),
    ],
    normalizationContext: ['groups' => ['get']]
)]
class Recipe
{
    use HasIdNameSlugAssertTrait;
    use HasContentTrait;
    use HasViewsTrait;
    use HasTimestampTrait;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['get'])]
    private ?int $preparation = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['get'])]
    private ?int $break = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['get'])]
    private ?int $cooking = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 1])]
    #[Groups(['get'])]
    #[Assert\NotNull]
    private ?bool $draft = true;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 1])]
    #[Assert\NotNull(groups: ['create', 'update'])]
    private bool $enablereviews = true;

    #[ORM\ManyToOne(inversedBy: 'recipes', cascade: ['persist'])]
    private ?HomepageHeroSetting $isonhomepageslider = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?User $user = null;

    /**
     * @var Collection<int, UserRecipe>
     */
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: UserRecipe::class)]
    #[Groups(['get'])]
    private Collection $userRecipes;

    /**
     * @var Collection<int, Step>
     */
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Step::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[Groups(['get'])]
    private Collection $steps;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'recipes')]
    #[Groups(['get'])]
    private Collection $tags;

    /**
     * @var Collection<int, Thumbnail>
     */
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Thumbnail::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[Groups(['get'])]
    private Collection $thumbnails;

    /**
     * @var Collection<int, RecipeHasSource>
     */
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeHasSource::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[Groups(['get'])]
    private Collection $recipeHasSources;

    /**
     * @var Collection<int, RecipeHasIngredient>
     */
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeHasIngredient::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[Groups(['get'])]
    private Collection $recipeHasIngredients;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Review::class, cascade: ['persist', 'remove'])]
    private Collection $reviews;

    /**
     * @var Collection<int, User>.
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'favorites', cascade: ['persist', 'merge'])]
    #[ORM\JoinTable(name: 'favorites')]
    #[ORM\JoinColumn(name: 'recipe_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private Collection $addedtofavoritesby;

    public function __toString(): string
    {
        return $this->getName().' ('.$this->getId().')';
    }

    public function __construct()
    {
        $this->userRecipes = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->thumbnails = new ArrayCollection();
        $this->recipeHasSources = new ArrayCollection();
        $this->recipeHasIngredients = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->addedtofavoritesby = new ArrayCollection();
    }

    public function getPreparation(): ?int
    {
        return $this->preparation;
    }

    public function setPreparation(?int $preparation): static
    {
        $this->preparation = $preparation;

        return $this;
    }

    public function getBreak(): ?int
    {
        return $this->break;
    }

    public function setBreak(?int $break): static
    {
        $this->break = $break;

        return $this;
    }

    public function getCooking(): ?int
    {
        return $this->cooking;
    }

    public function setCooking(?int $cooking): static
    {
        $this->cooking = $cooking;

        return $this;
    }

    public function isDraft(): ?bool
    {
        return $this->draft;
    }

    public function setDraft(bool $draft): static
    {
        $this->draft = $draft;

        return $this;
    }

    public function isEnablereviews(): bool
    {
        return $this->enablereviews;
    }

    public function getEnablereviews(): bool
    {
        return $this->enablereviews;
    }

    public function setEnablereviews(bool $enablereviews): static
    {
        $this->enablereviews = $enablereviews;

        return $this;
    }

    public function getIsonhomepageslider(): ?HomepageHeroSetting
    {
        return $this->isonhomepageslider;
    }

    public function setIsonhomepageslider(?HomepageHeroSetting $isonhomepageslider): static
    {
        $this->isonhomepageslider = $isonhomepageslider;

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
            $userRecipe->setRecipe($this);
        }

        return $this;
    }

    public function removeUserRecipe(UserRecipe $userRecipe): static
    {
        if ($this->userRecipes->removeElement($userRecipe)) {
            // set the owning side to null (unless already changed)
            if ($userRecipe->getRecipe() === $this) {
                $userRecipe->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Step>
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): static
    {
        if (!$this->steps->contains($step)) {
            $this->steps->add($step);
            $step->setRecipe($this);
        }

        return $this;
    }

    public function removeStep(Step $step): static
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getRecipe() === $this) {
                $step->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addRecipe($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeRecipe($this);
        }

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
            $thumbnail->setRecipe($this);
        }

        return $this;
    }

    public function removeThumbnail(Thumbnail $thumbnail): static
    {
        if ($this->thumbnails->removeElement($thumbnail)) {
            // set the owning side to null (unless already changed)
            if ($thumbnail->getRecipe() === $this) {
                $thumbnail->setRecipe(null);
            }
        }

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
            $recipeHasSource->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeHasSource(RecipeHasSource $recipeHasSource): static
    {
        if ($this->recipeHasSources->removeElement($recipeHasSource)) {
            // set the owning side to null (unless already changed)
            if ($recipeHasSource->getRecipe() === $this) {
                $recipeHasSource->setRecipe(null);
            }
        }

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
            $recipeHasIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeHasIngredient(RecipeHasIngredient $recipeHasIngredient): static
    {
        if ($this->recipeHasIngredients->removeElement($recipeHasIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeHasIngredient->getRecipe() === $this) {
                $recipeHasIngredient->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setRecipe($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getRecipe() === $this) {
                $review->setRecipe(null);
            }
        }

        return $this;
    }

    public function isRatedBy(User $user): Review
    {
        foreach ($this->reviews as $review) {
            if ($review->getUser() === $user) {
                return $review;
            }
        }

        return false;
    }

    public function getRatingsPercentageForRating($rating): int|float
    {
        if (!$this->countVisibleReviews()) {
            return 0;
        }

        return round(($this->getRatingsCountForRating($rating) / $this->countVisibleReviews()) * 100, 1);
    }

    public function getRatingsCountForRating($rating): int
    {
        if (!$this->countVisibleReviews()) {
            return 0;
        }

        $ratingCount = 0;

        foreach ($this->reviews as $review) {
            if ($review->getVisible() && $review->getRating() === $rating) {
                ++$ratingCount;
            }
        }

        return $ratingCount;
    }

    public function getRatingAvg(): int|float
    {
        if (!$this->countVisibleReviews()) {
            return 0;
        }
        $ratingAvg = 0;

        foreach ($this->reviews as $review) {
            if ($review->getVisible()) {
                $ratingAvg += $review->getRating();
            }
        }

        return round($ratingAvg / $this->countVisibleReviews(), 1);
    }

    public function getRatingPercentage(): int|float
    {
        if (!$this->countVisibleReviews()) {
            return 0;
        }

        $ratingPercentage = 0;

        foreach ($this->reviews as $review) {
            if ($review->getVisible()) {
                $ratingPercentage += $review->getRatingPercentage();
            }
        }

        return round($ratingPercentage / $this->countVisibleReviews(), 1);
    }

    public function countVisibleReviews(): int
    {
        $count = 0;

        foreach ($this->reviews as $review) {
            if ($review->getVisible()) {
                ++$count;
            }
        }

        return $count;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAddedtofavoritesby(): Collection
    {
        return $this->addedtofavoritesby;
    }

    public function addAddedtofavoritesby(User $addedtofavoritesby): static
    {
        if (!$this->addedtofavoritesby->contains($addedtofavoritesby)) {
            $this->addedtofavoritesby->add($addedtofavoritesby);
        }

        return $this;
    }

    public function removeAddedtofavoritesby(User $addedtofavoritesby): static
    {
        $this->addedtofavoritesby->removeElement($addedtofavoritesby);

        return $this;
    }

    public function isAddedToFavoritesBy(User $user): bool
    {
        return $this->addedtofavoritesby->contains($user);
    }
}
