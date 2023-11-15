<?php

namespace CustomListing\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

class CustomerPhoneNumber extends Constraint
{
    final public const PHONE_NUMBER_INVALID = 'PHONE_NUMBER_INVALID';


    /**
     * @var array<string, string>
     */
    protected static $errorNames
        = [
            self::PHONE_NUMBER_INVALID => 'PHONE_NUMBER_INVALID',
        ];

    private string $message = 'This value is not a valid phone number';

    public function getMessage(): string
    {
        return $this->message;
    }
}
