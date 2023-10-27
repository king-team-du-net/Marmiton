<?php

namespace App\Entity\Pages;

use App\Entity\Traits\HasIdTrait;
use App\Entity\Traits\HasIPTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Repository\Pages\ContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\String\u;

/** Saves contact requests to limit spam. */
#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    use HasIdTrait;
    use HasIPTrait;
    use HasTimestampTrait;

    #[Assert\NotBlank]
    #[Assert\Length(min: 4, max: 100)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $fullname = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\NotNull]
    #[Assert\Length(min: 5, max: 180)]
    #[ORM\Column(type: Types::STRING, length: 180)]
    private ?string $email = null;

    #[Assert\Length(min: 10, max: 20)]
    #[Assert\Regex(
        pattern: '/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/',
        message: 'Invalid phone number.',
    )]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $phone = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $company = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $subject = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 10)]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isSend = false;

    public function getFullname(): ?string
    {
        return u($this->fullname)->upper()->toString();
    }

    public function setFullname(string $fullname): static
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function isIsSend(): bool
    {
        return $this->isSend;
    }

    public function getIsSend(): bool
    {
        return $this->isSend;
    }

    public function setIsSend(bool $isSend): static
    {
        $this->isSend = $isSend;

        return $this;
    }
}
