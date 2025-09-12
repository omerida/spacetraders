<?php

namespace Phparch\SpaceTraders;

use GuzzleHttp\Psr7\Response;
use Twig\Environment;

interface TwigAwareInterface
{
    public function setTwigEnvironment(Environment $twig): void;
    /**
     * @param array<string, mixed> $parameters
     */
    public function render(string $template, array $parameters = [], int $status = 200): Response;
}
