<?php

namespace Phparch\SpaceTraders\Controller;

use Phparch\SpaceTraders\RequestAwareInterface;
use Psr\Http\Message\ServerRequestInterface;

class RequestAwareController implements RequestAwareInterface
{
    private ServerRequestInterface $request;

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function setRequest(
        ServerRequestInterface $request
    ): void
    {
        $this->request = $request;
    }
}
