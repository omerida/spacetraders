<?php

namespace Phparch\SpaceTradersCLI\Command;

#[\Attribute]
class HelpInfo
{
    public function __construct(
        public string $description,
        public ?array $params = null,
        public ?array $required = null,
    ) {
    }
}
