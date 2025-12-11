<?php

namespace Phparch\SpaceTraders\Trait;

use CuyZ\Valinor;

trait MapFromArray
{
    /**
     * @throws Valinor\Mapper\MappingError
     */
    public static function fromArray(mixed $data): static
    {
        try {
            return (new Valinor\MapperBuilder())
                ->mapper()
                ->map(
                    static::class,
                    Valinor\Mapper\Source\Source::iterable($data) /* @phpstan-ignore-line */
                );
        } catch (\CuyZ\Valinor\Mapper\MappingError $error) {
            $messages = \CuyZ\Valinor\Mapper\Tree\Message\Messages::flattenFromNode(
                $error->node()
            );
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
