<?php

namespace Phparch\SpaceTradersCLI\Render;

class AbstractRenderer implements RenderInterface
{
    /**
     * @var string[]
     */
    private $out = [];

    protected function writeln(string ...$lines) {
        $this->out = array_merge($this->out, $lines);
    }

    protected function sprintf(...$params) {
        $this->out[] = sprintf(...$params);
    }

    public function output(): string
    {
        return implode(PHP_EOL, $this->out) . PHP_EOL;
    }
}