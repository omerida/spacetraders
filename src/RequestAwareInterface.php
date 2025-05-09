<?php

namespace Phparch\SpaceTraders;

use Psr\Http\Message\ServerRequestInterface;

interface RequestAwareInterface
{
    public function getRequest(): ServerRequestInterface;

    public function setRequest(
        ServerRequestInterface $request
    ): void;
}
