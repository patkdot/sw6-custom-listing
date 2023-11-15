<?php

namespace CustomListing\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CustomerPhoneNumberValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof CustomerPhoneNumber) {
            throw new UnexpectedTypeException($constraint, __CLASS__);
        }

        if (!empty($value) && !str_starts_with($value, '+')) {
            $this->context->buildViolation($constraint->getMessage())
                ->setCode(CustomerPhoneNumber::PHONE_NUMBER_INVALID)
                ->addViolation();
        }
    }
}
