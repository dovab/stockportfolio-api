<?php

namespace App\Application\Interface;

use App\Entity\User;

interface HasUserInterface
{
    /**
     * Returns the User for the entity
     *
     * @return User|null
     */
    public function getUser(): User|null;

    /**
     * Sets the User for the entity
     *
     * @param User $user
     *
     * @return self
     */
    public function setUser(User $user): self;
}