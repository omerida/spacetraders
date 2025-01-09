<?php

namespace Phparch\SpaceTradersCLI\Command;

#[\Attribute]
class HelpInfo
{
    public function __construct(
        public string $description,
        /** @var string[] */
        public ?array $params = null,
        /** @var string[] */
        public ?array $required = null,
    ) {
    }
}
