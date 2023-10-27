<?php

namespace App\Entity\Traits;

use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait HasInvitationDetailsTrait
{
    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0])]
    private bool $suspended = false;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0])]
    private bool $is_verified = false;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $resetToken = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $resetTokenLifeTime = null;

    /*
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $resetPasswordToken = null;
    */

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $resetPasswordToken = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['default' => null])]
    private ?\DateTimeInterface $lastLogin = null;

    #[ORM\Column(type: Types::STRING, nullable: true, options: ['default' => null])]
    private ?string $lastLoginIp = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $bannedAt = null;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0], nullable: false)]
    private int $invitationDuration = 0;

    public function isSuspended(): bool
    {
        return $this->suspended;
    }

    public function setSuspended(bool $suspended): static
    {
        $this->suspended = $suspended;

        return $this;
    }

    public function isIsVerified(): bool
    {
        return $this->is_verified;
    }

    public function getIsVerified(): bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): static
    {
        $this->is_verified = $is_verified;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): User
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function hasValidatedReset(): bool
    {
        return null === $this->resetToken;
    }

    public function getResetTokenLifeTime(): ?\DateTime
    {
        return $this->resetTokenLifeTime;
    }

    public function setResetTokenLifeTime(\DateTime $resetTokenLifeTime): static
    {
        $this->resetTokenLifeTime = $resetTokenLifeTime;

        return $this;
    }

    /*
    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    public function setResetPasswordToken(?string $resetPasswordToken): User
    {
        $this->resetPasswordToken = $resetPasswordToken;

        return $this;
    }

    public function hasValidatedResetPassword(): bool
    {
        return null === $this->resetPasswordToken;
    }
    */

    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    public function setResetPasswordToken(?string $resetPasswordToken): static
    {
        $this->resetPasswordToken = $resetPasswordToken;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTimeInterface $lastLogin): static
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getLastLoginIp(): ?string
    {
        return $this->lastLoginIp;
    }

    public function setLastLoginIp(?string $lastLoginIp): static
    {
        $this->lastLoginIp = $lastLoginIp;

        return $this;
    }

    public function getBannedAt(): ?\DateTimeInterface
    {
        return $this->bannedAt;
    }

    public function setBannedAt(?\DateTimeInterface $bannedAt): static
    {
        $this->bannedAt = $bannedAt;

        return $this;
    }

    public function isBanned(): bool
    {
        return null !== $this->bannedAt;
    }

    public function canLogin(): bool
    {
        return !$this->isBanned() && null === $this->getResetToken();
    }

    public function getInvitationDuration(): int
    {
        return $this->invitationDuration;
    }

    public function setInvitationDuration(int $invitationDuration): static
    {
        $this->invitationDuration = $invitationDuration;

        return $this;
    }
}
