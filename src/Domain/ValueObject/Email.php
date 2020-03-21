<?php

namespace App\Domain\ValueObject;

use Webmozart\Assert\Assert;

class Email extends StringValueObject
{
    public function __construct(string $email)
    {
        Assert::email($email, 'Non valid e-mail provided');
        parent::__construct($email);
    }
}