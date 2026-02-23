<?php

namespace Phparch\SpaceTraders\Value;

final class Orbital
{
    public function __construct(
        /** @var non-empty-string */
        public string $symbol {
            set {
                if (empty(trim($value))) {
                    throw new \InvalidArgumentException('symbol cannot be empty');
                }
                $this->symbol = $value;
            }
        },
    ) {
    }
}
