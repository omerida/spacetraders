<?php

namespace Phparch\SpaceTraders\Middleware;

use Lcobucci\JWT\Token\InvalidTokenStructure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use League\Route\Http\Exception\UnauthorizedException;
use GuzzleHttp\Psr7;

class Auth implements MiddlewareInterface
{
    public function __construct(
        private readonly ?string $token
    ) {
    }

    /**
     * @throws UnauthorizedException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if ($request->getRequestTarget() === '/get-new-token') {
            return $handler->handle($request);
        }

        if ($this->token) {
            try {
                $parser = new Parser(new JoseEncoder());
                /** @var \Lcobucci\JWT\Token\Plain $token */
                $token = $parser->parse($this->token);

                if ($token->claims()->get('sub') === 'agent-token') {
                    return $handler->handle($request);
                }
            } catch (InvalidTokenStructure $e) {
                // Send users to store a new token
                return $this->redirect();
            }
        }
        return $this->redirect();
    }

    private function redirect(): ResponseInterface {
        return new Psr7\Response(
            status: 307,
            headers: ['Location' => '/get-new-token']
        );
    }
}
