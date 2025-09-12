<?php

namespace Phparch\SpaceTraders\Middleware;

use GuzzleHttp\Psr7\Response;
use Phparch\SpaceTraders\APIException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

class ExceptionDecorator implements MiddlewareInterface
{
    protected Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (APIException $e) {
            $message = $e->getMessage() . ' (' . $e->getCode() . ')';
            $status = 400;
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            $status = 500;
        }
        $response = new Response();

        $response->getBody()->write(
            $this->twig->render('error-message.html.twig', [
                'message' => $message
            ])
        );
        return $response->withStatus($status);
    }
}
