<?php

namespace Phparch\SpaceTraders\Value\System;

class SystemTrait
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
        /** @var non-empty-string */
        public string $name {
            set {
                if (empty(trim($value))) {
                    throw new \InvalidArgumentException('name cannot be empty');
                }
                $this->name = $value;
            }
        },
        /** @var non-empty-string */
        public string $description {
            set {
                if (empty(trim($value))) {
                    throw new \InvalidArgumentException('description cannot be empty');
                }
                $this->description = $value;
            }
        },
    ) {
    }
}
