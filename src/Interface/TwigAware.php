<?php

namespace Phparch\SpaceTraders\Interface;

use GuzzleHttp\Psr7\Response;
use Twig\Environment;

interface TwigAware
{
    public function setTwigEnvironment(Environment $twig): void;
    /**
     * @param array<string, mixed> $parameters
     */
    public function render(string $template, array $parameters = [], int $status = 200): Response;
}
