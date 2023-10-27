<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait HasDeletedAtTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?\DateTime $deletedAt = null;

    /**
     * Get the deleted at timestamp value. Will return null if
     * the entity has not been soft deleted.
     */
    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    /**
     * Set or clear the deleted at timestamp.
     */
    public function setDeletedAt(?\DateTime $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Check if the entity has been soft deleted.
     */
    public function isDeleted(): bool
    {
        return null !== $this->deletedAt;
    }
}
