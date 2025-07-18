<?php

namespace Phparch\SpaceTraders\Controller\Trait;

use Phparch\SpaceTraders\RequestAwareInterface;
use Psr\Http\Message\ServerRequestInterface;

trait RequestAwareController
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
