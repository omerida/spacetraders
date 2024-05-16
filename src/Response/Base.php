<?php

namespace Phparch\SpaceTraders\Response;

use CuyZ\Valinor;

abstract class Base
{
    public static function fromArray(mixed $data): static
    {
        try {
            return (new Valinor\MapperBuilder())
                ->enableFlexibleCasting()
                ->allowSuperfluousKeys()
                ->allowPermissiveTypes()
                ->mapper()
                ->map(
                    static::class,
                    Valinor\Mapper\Source\Source::iterable($data)
                );
        } catch (\CuyZ\Valinor\Mapper\MappingError $error) {
            $messages = \CuyZ\Valinor\Mapper\Tree\Message\Messages::flattenFromNode(
                $error->node()
            );
            // If only errors are wanted, they can be filtered
            $errorMessages = $messages->errors();

            foreach ($errorMessages as $message) {
                echo $message . PHP_EOL;
            }
        }
    }
}