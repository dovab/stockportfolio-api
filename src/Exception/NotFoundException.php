<?php

namespace App\Exception;

class NotFoundException extends AppException
{
    /**
     * @param string $entity
     *
     * @return NotFoundException
     */
    public static function createEntityNotFoundException(string $entity): NotFoundException
    {
        return new self(sprintf('The requested %s was not found', $entity));
    }
}