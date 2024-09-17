<?php

namespace Phparch\SpaceTradersCLI\Render\Shipyard;

use \Phparch\SpaceTraders\Value;
use \Phparch\SpaceTradersCLI\Render\AbstractRenderer;

class Ship extends AbstractRenderer {
        public function __construct(
        public Value\Shipyard\Ship $ship,
    ) {
    }

    public function output(): string
    {
        return parent::output();
    }
}