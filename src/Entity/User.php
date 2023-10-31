<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Entity\Blog\Article;
use App\Entity\Recipe\Recipe;
use App\Entity\Recipe\UserRecipe;
use App\Entity\Setting\HomepageHeroSetting;
use App\Entity\Traits\HasAvatarVichTrait;
use App\Entity\Traits\HasIdTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use function Symfony\Component\String\u;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'This email address is already in use.')]
#[UniqueEntity(fields: ['nickname'], message: 'This nickname is already used.')]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: [
                'groups' => ['get', 'User:item:get'],
            ],
            security: "is_granted('ROLE_ADMIN') or object == user"
        ),
        new Patch(security: "is_granted('ROLE_ADMIN') or object == user"),
        new GetCollection(security: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['get']],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Stringable, JWTUserInterface
{
    use HasIdTrait;
    use HasAvatarVichTrait;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0])]
    private bool $suspended = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['get'])]
    private ?string $about = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['get'])]
    private ?string $designation = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0])]
    #[Assert\NotNull]
    #[Groups(['get'])]
    private bool $team = false;

    #[Assert\Length(min: 2, max: 20)]
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 20)]
    private string $firstname = '';

    #[Assert\Length(min: 2, max: 20)]
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 20)]
    private string $lastname = '';

    #[ORM\Column(type: Types::STRING, length: 30, unique: true)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 4, max: 30)]
    #[Groups(['get'])]
    private string $nickname = '';

    #[ORM\Column(length: 30, unique: true)]
    #[Gedmo\Slug(fields: ['nickname'], unique: true, updatable: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    #[Assert\Email]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Groups(['get'])]
    private string $email = '';

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['get'])]
    private array $roles = [HasRoles::DEFAULT];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: Types::STRING)]
    private string $password = '';

    #[Assert\NotBlank(groups: ['password'])]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
        htmlPattern: '^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$',
        groups: ['password']
    )]
    #[Assert\Length(min: 8)]
    private ?string $plainPassword = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?HomepageHeroSetting $isuseronhomepageslider = null;

    /**
     * @var collection<int, Recipe>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Recipe::class, cascade: ['persist', 'remove'])]
    #[Groups(['User:item:get'])]
    private Collection $recipes;

    /**
     * @var Collection<int, UserRecipe>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserRecipe::class)]
    #[Groups(['User:item:get'])]
    private Collection $userRecipes;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class, cascade: ['persist', 'remove'])]
    private Collection $reviews;

    /**
     * @var Collection<int, Testimonial>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Testimonial::class, cascade: ['persist', 'remove'])]
    private Collection $testimonials;

    /**
     * @var Collection<int, Recipe>
     */
    #[ORM\ManyToMany(targetEntity: Recipe::class, mappedBy: 'addedtofavoritesby', fetch: 'LAZY', cascade: ['remove'])]
    private Collection $favorites;

    /**
     * @var Collection<int, Article>
     */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Article::class, orphanRemoval: true)]
    private Collection $articles;

    public function __toString(): string
    {
        return $this->nickname ?? $this->email;
    }

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
        $this->userRecipes = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->testimonials = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function isSuspended(): bool
    {
        return $this->suspended;
    }

    public function setSuspended(bool $suspended): static
    {
        $this->suspended = $suspended;

        return $this;
    }

    public function getAbout(): ?string
    {
        return u($this->about)->upper()->toString();
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

    public function getFullName(): string
    {
        return u(sprintf('%s %s', $this->firstname, $this->lastname))->upper()->toString();
    }

    public function getFirstname(): ?string
    {
        return u($this->firstname)->upper()->toString();
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return u($this->lastname)->upper()->toString();
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): static
    {
        $this->nickname = trim($nickname ?: '');

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email ?: '';

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = HasRoles::DEFAULT;

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password ?: '';

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public static function createFromPayload($username, array $payload): self
    {
        return (new self())
            // ->setId($username)
            ->setRoles($payload['roles'])
            ->setEmail($payload['email'])
        ;
    }

    public function __serialize(): array
    {
        return [
            $this->id,
            $this->firstname,
            $this->lastname,
            $this->nickname,
            $this->email,
            $this->password,
            $this->suspended,
        ];
    }

    public function __unserialize(array $data): void
    {
        if (7 === count($data)) {
            [
                $this->id,
                $this->firstname,
                $this->lastname,
                $this->nickname,
                $this->email,
                $this->password,
                $this->suspended,
            ] = $data;
        }
    }

    public function getIsuseronhomepageslider(): ?HomepageHeroSetting
    {
        return $this->isuseronhomepageslider;
    }

    public function setIsuseronhomepageslider(?HomepageHeroSetting $isuseronhomepageslider): static
    {
        $this->isuseronhomepageslider = $isuseronhomepageslider;

        return $this;
    }

    /**
     * @return collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setUser($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getUser() === $this) {
                $recipe->setUser(null);
            }
        }

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
            $userRecipe->setUser($this);
        }

        return $this;
    }

    public function removeUserBook(UserRecipe $userRecipe): static
    {
        if ($this->userRecipes->removeElement($userRecipe)) {
            // set the owning side to null (unless already changed)
            if ($userRecipe->getUser() === $this) {
                $userRecipe->setUser(null);
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
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Testimonial>
     */
    public function getTestimonials(): Collection
    {
        return $this->testimonials;
    }

    public function addTestimonial(Testimonial $testimonial): static
    {
        if (!$this->testimonials->contains($testimonial)) {
            $this->testimonials->add($testimonial);
            $testimonial->setUser($this);
        }

        return $this;
    }

    public function removeTestimonial(Testimonial $testimonial): static
    {
        if ($this->testimonials->removeElement($testimonial)) {
            // set the owning side to null (unless already changed)
            if ($testimonial->getUser() === $this) {
                $testimonial->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Recipe $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->addAddedtofavoritesby($this);
        }

        return $this;
    }

    public function removeFavorite(Recipe $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            $favorite->removeAddedtofavoritesby($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setAuthor($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

        return $this;
    }
}
