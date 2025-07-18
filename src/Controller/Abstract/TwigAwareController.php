<?php

namespace Phparch\SpaceTraders\Controller\Abstract;

use GuzzleHttp\Psr7\Response;
use Twig\Environment;

abstract class TwigAwareController
{
    protected Environment $twig;

    public function setTwigEnvironment(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(string $template, array $parameters = [], int $status = 200): Response
    {
        $response = new Response();
        $response->getBody()->write(
            $this->twig->render($template, $parameters)
        );
        return $response->withStatus($status);
    }
}
