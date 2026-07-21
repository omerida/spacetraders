<?php

namespace Event;

use Crell\Tukio\Dispatcher;
use Crell\Tukio\OrderedListenerProvider;
use DI\Container;
use GuzzleHttp\Psr7;
use Phparch\SpaceTraders\Event\ListenerService;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTradersRest\Event\ContractAccepted;
use Phparch\SpaceTradersRest\Client;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

class ContractAcceptedListenerTest extends TestCase
{
    public function testListenerFires(): void
    {
        // Set up a container that will return a partial mock
        // of our Listener service.
        $container = new Container([
            ListenerService::class => function () {
                $mock = $this->getMockBuilder(ListenerService::class)
                    ->onlyMethods(['onContractAccepted'])
                    ->getMock();
                $mock->expects($this->once())
                    ->method('onContractAccepted')
                    ->with($this->isInstanceOf(ContractAccepted::class));
                return $mock;
            }
        ]);

        // Go through the setup of an event provider and listener
        // just like we would in config/services.php
        $provider = new OrderedListenerProvider($container);
        // register events based on attributes on methods in ListenerService
        $provider->listenerService(ListenerService::class);
        $dispatcher = new Dispatcher($provider);

        // Stub the API response in Guzzle
        $guzzle = $this->createStub(\GuzzleHttp\Client::class);
        $guzzle->method('post')
            ->willReturn($this->getAcceptedResponse());
        $client = new Client\Contracts(
            token: "SECRET_TOKEN",
            guzzle: $guzzle,
            eventDispatcher: $dispatcher
        );

        // Pretend to call the Spacetraders API
        $client->accept("FOO");
    }

    public function testListenerDoesNotFire(): void
    {
        // We must create the mock directly and not in the
        // container so that phpunit knows about our
        // expects() for the mocked method.
        $mockListener = $this->getMockBuilder(ListenerService::class)
            ->onlyMethods(['onContractAccepted'])
            ->getMock();

        $mockListener->expects($this->never())
            ->method('onContractAccepted');

        // Set up a container that will return a partial mock
        // of our Listener service.
        $container = new Container([
            ListenerService::class => fn() => $mockListener
        ]);

        // Go through the setup of an event provider and listener
        // just like we would in config/services.php
        $provider = new OrderedListenerProvider($container);
        // register events based on attributes on methods in ListenerService
        $provider->listenerService(ListenerService::class);
        $dispatcher = new Dispatcher($provider);

        // Stub the API response in Guzzle
        $guzzle = $this->createStub(\GuzzleHttp\Client::class);
        $guzzle->method('post')
            ->willReturn($this->getNotAcceptedResponse());
        $client = new Client\Contracts(
            token: "SECRET_TOKEN",
            guzzle: $guzzle,
            eventDispatcher: $dispatcher
        );
        $client->accept("FOO");
    }

    private function getAcceptedResponse(): Psr7\Response
    {
        $json = <<<JSON
{
  "data": {
    "contract": {
      "id": "string",
      "factionSymbol": "string",
      "type": "PROCUREMENT",
      "terms": {
        "deadline": "2026-07-09T03:51:24.696Z",
        "payment": {
          "onAccepted": 1,
          "onFulfilled": 1
        },
        "deliver": [
          {
            "tradeSymbol": "string",
            "destinationSymbol": "string",
            "unitsRequired": 1,
            "unitsFulfilled": 1
          }
        ]
      },
      "accepted": true,
      "fulfilled": false,
      "deadlineToAccept": "2026-07-09T03:51:24.696Z",
      "expiration": "2026-07-09T03:51:24.696Z"
    },
    "agent": {
      "accountId": "string",
      "symbol": "string",
      "headquarters": "X1-UQ87-A1",
      "credits": 1,
      "startingFaction": "COSMIC",
      "shipCount": 1
    }
  }
}
JSON;

        return new Psr7\Response(200, [], $json);
    }

    private function getNotAcceptedResponse(): Psr7\Response
    {
        $json = <<<JSON
{
  "data": {
    "contract": {
      "id": "string",
      "factionSymbol": "string",
      "type": "PROCUREMENT",
      "terms": {
        "deadline": "2026-07-09T03:51:24.696Z",
        "payment": {
          "onAccepted": 1,
          "onFulfilled": 1
        },
        "deliver": [
          {
            "tradeSymbol": "string",
            "destinationSymbol": "string",
            "unitsRequired": 1,
            "unitsFulfilled": 1
          }
        ]
      },
      "accepted": false,
      "fulfilled": false,
      "deadlineToAccept": "2026-07-09T03:51:24.696Z",
      "expiration": "2026-07-09T03:51:24.696Z"
    },
    "agent": {
      "accountId": "string",
      "symbol": "string",
      "headquarters": "X1-UQ87-A1",
      "credits": 1,
      "startingFaction": "COSMIC",
      "shipCount": 1
    }
  }
}
JSON;

        return new Psr7\Response(200, [], $json);
    }
}