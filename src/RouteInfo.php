<?php

namespace Phparch\SpaceTraders;

class RouteInfo
{
    /**
     * @param string[] $httpMethods
     */
    public function __construct(
        public readonly string $name,
        public readonly string $path,
        public readonly array $httpMethods,
        public readonly string $class,
        public readonly string $method,
    ) {
    }
}
