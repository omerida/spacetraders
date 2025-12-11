<?php

namespace Phparch\SpaceTraders\Client;

use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Response;
use Phparch\SpaceTraders\Value\Contract;

class Contracts extends Client
{
    public function myContracts(): \Phparch\SpaceTraders\Value\Contracts
    {
        return $this->convertResponse(
            $this->get('my/contracts'),
            \Phparch\SpaceTraders\Value\Contracts::class
        );
    }

    public function accept(string $id): Contract\Accept
    {
        $url = sprintf('my/contracts/%s/accept', $id);

        return $this->convertResponse(
            $this->post($url),
            Contract\Accept::class
        );
    }

    public function details(string $id): Contract
    {
        $url = sprintf('my/contracts/%s', $id);

        return $this->convertResponse(
            $this->get($url),
            Contract::class,
        );
    }
}
