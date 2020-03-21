<?php

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /**
     * @dataProvider nonValidEmails
     */
    public function test_non_valid_emails_are_not_accepted(string $nonValidEmail)
    {
        $this->expectException(InvalidArgumentException::class);

        new Email($nonValidEmail);
    }

    public function nonValidEmails()
    {
        yield 'no @ character' => ['Abc.example.com'];
        yield 'only one @ is allowed outside quotation marks' => ['A@b@c@example.com'];
        yield 'none of the special characters in this local-part are allowed outside quotation marks' => ['a"b(c)d,e:f;g<h>i[j\k]l@example.com'];
        yield 'quoted strings must be dot separated or the only element making up the local-part' => ['just"not"right@example.com'];
        yield 'spaces, quotes, and backslashes may only exist when within quoted strings and preceded by a backslash' => ['this is"not\allowed@example.com'];
        yield 'even if escaped (preceded by a backslash), spaces, quotes, and backslashes must still be contained by quotes' => ['this\ still\"not\\allowed@example.com'];
        yield 'local part is longer than 64 characters' => ['1234567890123456789012345678901234567890123456789012345678901234+x@example.com'];
    }
}