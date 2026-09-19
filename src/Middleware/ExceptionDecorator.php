<?php

namespace Phparch\SpaceTraders\Middleware;

use GuzzleHttp\Psr7\Response;
use Phparch\SpaceTradersRest\Exception\APIAuthentication;
use Phparch\SpaceTradersRest\Exception\APIFailure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

class ExceptionDecorator implements MiddlewareInterface
{
    public function __construct(protected Environment $twig)
    {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        try {
            return $handler->handle($request);
        } catch (APIAuthentication $e) {
            return new Response(
                status: 307,
                headers: ['Location' => '/get-new-token']
            );
        } catch (APIFailure $e) {
            $message = $e->getMessage() . ' (' . $e->getCode() . ')';
            $status = 500;
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
        switch ($mode) {
            case 'drawer':
            case 'modal':
                if (
                    $val = json_encode([
                        'target' => '.errors',
                        'mode' => $mode,
                        JSON_THROW_ON_ERROR
                    ])
                ) {
                    $headers['X-Up-Open-Layer'] = $val;
                }
                $minimalTemplate = true;
                break;
            case 'root':
                $minimalTemplate = true;
                if (
                    $val = json_encode([
                        'target' => '.errors',
                        'mode' => 'modal',
                        JSON_THROW_ON_ERROR
                    ])
                ) {
                    $headers['X-Up-Open-Layer'] = $val;
                }
                break;
        }

        $response = new Response(
            status: $status,
            headers: $headers
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
