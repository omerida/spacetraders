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

        // If the response was for a modal or drawer container,
        // then display the error in the same way.
        $mode = null;
        if ($request->hasHeader('X-Up-Mode')) {
            $mode = $request->getHeaderLine('X-Up-Mode');
        }

        $headers = [];
        $minimalTemplate = false;
        if (in_array($mode, ['modal', 'drawer'])) {
            $headers['X-Up-Open-Layer'] = json_encode([
               'target' => '.errors',
               'mode' => $mode,
            ]);
            $minimalTemplate = true;
        }

        $response = new Response(
            headers: $headers,
            status: $status
        );


        $response->getBody()->write(
            $this->twig->render('error-message.html.twig', [
                'message' => $message,
                'minimalTemplate' => $minimalTemplate,
            ])
        );
        return $response;
    }
}
