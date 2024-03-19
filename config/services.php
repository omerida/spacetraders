<?php

use Phparch\SpaceTraders;
use Phparch\SpaceTraders\ServiceContainer;

return [
  SpaceTraders\Client::class => function() {
    return new SpaceTraders\Client(
        $_ENV['SPACETRADERS_TOKEN'],
        ServiceContainer::get(GuzzleHttp\Client::class));
  }
];