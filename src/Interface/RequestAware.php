<?php

namespace Phparch\SpaceTraders\Interface;

use Psr\Http\Message\ServerRequestInterface;

interface RequestAware
{
    public function getRequest(): ServerRequestInterface;

    public function setRequest(
        ServerRequestInterface $request
    ): void;
}
