<?php

namespace Phparch\SpaceTraders\Middleware;

use Phparch\SpaceTraders\APIException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use League\Route\Http\Exception\UnauthorizedException;

class Auth implements MiddlewareInterface
{
    public function __construct(
        private readonly ?string $token
    )
    {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        if ($this->token) {
            try {
                $parser = new Parser(new JoseEncoder());
                $token = $parser->parse($this->token);
                if ($token->claims()->get('sub') === 'agent-token') {
                    return $handler->handle($request);
                }
            } catch (\Exception $e) {
//                trigger_error('Could not decode agent token '
//                    . $e->getMessage(), E_USER_WARNING);
                throw new UnauthorizedException(
                    'Unauthorized: ' . $e->getMessage(),
                    previous: $e
                );
            }
        }
    }
}
