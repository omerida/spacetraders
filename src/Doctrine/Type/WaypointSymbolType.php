<?php

namespace Phparch\SpaceTraders\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Phparch\SpaceTradersRest\Value\Waypoint\Symbol;

class WaypointSymbolType extends Type
{
    public const NAME = 'waypoint_symbol';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Symbol
    {
        if ($value === null) {
            return null;
        }

        if (is_scalar($value)) {
            return new Symbol((string) $value);
        }

        throw new \InvalidArgumentException('The value must be a scalar value.');
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Symbol) {
            return $value->waypoint;
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        throw new \InvalidArgumentException('The value must be a scalar value.');
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
