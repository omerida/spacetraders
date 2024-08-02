<?php

namespace Phparch\SpaceTradersCLI\Command;

#[\Attribute] #[Attribute]
class HelpInfo {
    public function __construct(
        public string $description,
        public ?array $params = null,
    ) {}
}