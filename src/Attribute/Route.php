<?php

namespace Phparch\SpaceTraders\Attribute;

#[\Attribute]
class Route
{
    public function __construct(
        public string $name,
        public string $path,
        /**
         * @var string[]
         */
        public array $methods,
    ) {
    }
}
