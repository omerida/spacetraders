<?php

namespace Phparch\SpaceTraders\Automation\Executor;

use Psr\Http\Message\ResponseInterface;

interface ExecutorInterface
{
    /**
     * @param array<string, mixed> $payload
     */
    public function setPayload(array $payload): void;
    public function execute(): void;
    public function getResponse(): ResponseInterface;
    public function shouldAdvance(): bool;
}
