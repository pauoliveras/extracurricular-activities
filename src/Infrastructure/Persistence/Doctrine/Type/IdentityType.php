<?php

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\ValueObject\Id;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Ramsey\Uuid\Doctrine\UuidType;

class IdentityType extends UuidType
{
    const NAME = 'identity';

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return Id::fromString($value);
    }

    public function getName()
    {
        return self::NAME;
    }

}