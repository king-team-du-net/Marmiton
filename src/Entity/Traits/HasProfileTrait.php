<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

trait HasProfileTrait
{
    use HasIdTrait;
    use HasAvatarVichTrait;
    use HasSocialMediaTrait;
    use HasEmailTrait;
    use HasIdentifyTrait;
    use HasTeamTrait;
    // use HasAddressTrait;
    // use HasKnpUOAuthLoggableTrait;
    use HasInvitationDetailsTrait;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 5, max: 30)]
    #[ORM\Column(type: Types::STRING, length: 30, unique: true)]
    private string $nickname = '';

    #[ORM\Column(length: 30, unique: true)]
    #[Gedmo\Slug(fields: ['nickname'], unique: true, updatable: true)]
    private ?string $slug = null;

    public function __toString(): string
    {
        return (string) $this->getFullName();
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
}
