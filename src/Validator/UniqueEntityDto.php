<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Validates the DTO for entity-uniqueness
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class UniqueEntityDto extends Constraint
{
    public string $field;
    public string $entityClass;
    public string $existsMessage;

    /**
     * @param null $options
     * @param array|null $groups
     * @param null $payload
     * @param null $field
     * @param null $entityClass
     * @param null $existsMessage
     */
    public function __construct($options = null, array $groups = null, $payload = null, $field = null, $entityClass = null, $existsMessage = null)
    {
        parent::__construct($options, $groups, $payload);

        $this->field = $field ?? $this->field;
        $this->entityClass = $entityClass ?? $this->entityClass;
        $this->existsMessage = $existsMessage ?? $this->existsMessage;
    }

    /**
     * Set the target of this constraint to class
     *
     * @return array|string
     */
    public function getTargets(): array|string
    {
        return self::CLASS_CONSTRAINT;
    }
}