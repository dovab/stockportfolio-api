<?php

namespace App\Infrastructure\Doctrine\Trait;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait SoftDeletableTrait
{
    #[ORM\Column(type: "datetime", nullable: true)]
    protected ?DateTime $deletedAt;

    /**
     * @param DateTime|null $deletedAt
     *
     * @return self
     */
    public function setDeletedAt(?DateTime $deletedAt = null): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return null !== $this->deletedAt;
    }
}
