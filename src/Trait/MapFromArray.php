<?php

namespace Phparch\SpaceTraders\Trait;

use CuyZ\Valinor;

trait MapFromArray
{
    /**
     * @throws Valinor\Mapper\MappingError
     * @param iterable<mixed> $data
     */
    public static function fromArray(iterable $data): static
    {
        try {
            return (new Valinor\MapperBuilder())
                ->mapper()
                ->map(
                    static::class,
                    Valinor\Mapper\Source\Source::iterable($data)
                );
        } catch (Valinor\Mapper\MappingError $error) {
            $messages = $error->messages();
            // If only errors are wanted, they can be filtered
            $errorMessages = $messages->errors();

            foreach ($errorMessages as $message) {
                $output = $message->withBody("{node_path}\n  {original_message}");
                echo $output . PHP_EOL;
            }

            throw $error;
        }
    }
}
