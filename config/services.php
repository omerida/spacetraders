<?php

use Phparch\SpaceTraders;

use Phparch\SpaceTraders\Client;

use Phparch\SpaceTraders\ServiceContainer;

return [
  Client\Agents::class => function() {
    return new Client\Agents(
        $_ENV['SPACETRADERS_TOKEN'],
        ServiceContainer::get(GuzzleHttp\Client::class));
  },
  Client\Contracts::class => function() {
    return new Client\Contracts(
        $_ENV['SPACETRADERS_TOKEN'],
        ServiceContainer::get(GuzzleHttp\Client::class));
  },
  Client\Fleet::class => function() {
    return new Client\Fleet(
        $_ENV['SPACETRADERS_TOKEN'],
        ServiceContainer::get(GuzzleHttp\Client::class));
  },
  Client\Systems::class => function() {
    return new Client\Systems(
        $_ENV['SPACETRADERS_TOKEN'],
        ServiceContainer::get(GuzzleHttp\Client::class));
  }
];