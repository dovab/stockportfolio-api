<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Validates the string as an JWT Token
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class JwtToken extends Constraint
{
    public string|null $action = null;

    public string $invalidMessage = 'The token is invalid.';
    public string $expiredMessage = 'The token is expired.';
    public string $unverifiedMessage = 'The token is unverified.';
    public string $noActionMessage = 'This token does not have an action.';
    public string $differentActionMessage = 'This token does not have the correct action.';

    public function __construct($options = null, array $groups = null, $payload = null, $action = null, $invalidMessage = null, $expiredMessage = null, $unverifiedMessage = null, $noActionMessage = null, $differentActionMessage = null)
    {
        parent::__construct($options, $groups, $payload);

        $this->action = $action ?? $this->action;
        $this->invalidMessage = $invalidMessage ?? $this->invalidMessage;
        $this->expiredMessage = $expiredMessage ?? $this->expiredMessage;
        $this->unverifiedMessage = $unverifiedMessage ?? $this->unverifiedMessage;
        $this->noActionMessage = $noActionMessage ?? $this->noActionMessage;
        $this->differentActionMessage = $differentActionMessage ?? $this->differentActionMessage;
    }
}