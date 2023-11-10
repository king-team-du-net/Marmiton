<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\String\u;

trait HasAddressTrait
{
    #[Assert\Length(min: 4, max: 50)]
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $addressline1 = '';

    #[Assert\Length(min: 4, max: 50)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $addressline2 = null;

    #[Assert\Length(min: 5, max: 5)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^[A-Za-z0-9]{2}\d{3}$/',
        message: 'Invalid postal code.',
        groups: ['order']
    )]
    #[ORM\Column(type: Types::STRING, length: 5)]
    private string $postalcode = '';

    #[Assert\Length(min: 4, max: 50)]
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $city = '';

    #[Assert\Length(min: 4, max: 50)]
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $state = '';

    #[Assert\Length(max: 50)]
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 50, options: ['default' => 'FR'])]
    private string $countrycode = '';

    public function stringifyAddress(): string
    {
        $address = '';

        if ($this->addressline1) {
            $address .= $this->addressline1.' ';
        }

        if ($this->addressline2) {
            $address .= $this->addressline2.' ';
        }

        if ($this->city) {
            $address .= $this->city.' ';
        }

        if ($this->state) {
            $address .= $this->state.' ';
        }

        if ($this->postalcode) {
            $address .= $this->postalcode.' ';
        }

        if ($this->countrycode) {
            $address .= $this->getCountryCode();
        }

        return $address;
    }

    public function getAddressLine1(): string
    {
        return u($this->addressline1)->upper()->toString();
    }

    public function setAddressLine1(string $addressline1): static
    {
        $this->addressline1 = $addressline1;

        return $this;
    }

    public function getAddressLine2(): ?string
    {
        return u($this->addressline2)->upper()->toString();
    }

    public function setAddressLine2(?string $addressline2): static
    {
        $this->addressline2 = $addressline2;

        return $this;
    }

    public function getPostalcode(): string
    {
        return u($this->postalcode)->upper()->toString();
    }

    public function setPostalcode(string $postalcode): static
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    public function getCity(): string
    {
        return u($this->city)->upper()->toString();
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): string
    {
        return u($this->state)->upper()->toString();
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getCountryCode(): string
    {
        return u($this->countrycode)->upper()->toString();
    }

    public function setCountryCode(string $countrycode): static
    {
        $this->countrycode = $countrycode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return Countries::getNames()[$this->countrycode] ?? null;
    }
}
