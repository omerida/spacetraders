<?php

namespace Phparch\SpaceTraders\Automation\Executor\Ship;

use GuzzleHttp\Psr7\Response;
use Phparch\SpaceTraders\Automation\Executor;
use Phparch\SpaceTraders\Repository;
use Phparch\SpaceTradersRest\Client;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;

abstract class AbstractExecutor implements Executor\ExecutorInterface
{
    private Environment $twig;
    protected bool $advance = false;

    public function __construct(
        protected readonly string $shipSymbol,
        protected Client\Fleet $fleet,
        protected Client\ShipTravel $shipTravel,
        protected Repository\Ship\OrderQueue $orderQueueRepo,
    ) {
    }

    public function shouldAdvance(): bool
    {
        return $this->advance;
    }

    public function setTwigEnvironment(Environment $twig): void
    {
        $this->twig = $twig;
    }

    /**
     * @param array<string, mixed> $payload
     */
    abstract public function setPayload(array $payload): void;
    abstract public function execute(): void;
    abstract public function getResponse(): ResponseInterface;

    /**
     * @param array<string, mixed> $parameters
     */
    public function render(string $template, array $parameters = [], int $status = 200): Response
    {
        $response = new Response();
        $response->getBody()->write(
            $this->twig->render($template, $parameters)
        );
        return $response->withStatus($status);
    }
}
