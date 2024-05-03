<?php

namespace Phparch\SpaceTraders\Response;

use CuyZ\Valinor;

abstract class Base
{
    public static function fromArray(array $data): static
    {
        return (new Valinor\MapperBuilder())
            ->mapper()
            ->map(
                static::class,
                Valinor\Mapper\Source\Source::iterable($data)
            );
    }
}