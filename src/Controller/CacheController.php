<?php

namespace Phparch\SpaceTraders\Controller;

use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Controller\Trait\RequestAwareController;
use Phparch\SpaceTraders\RequestAwareInterface;

class CacheController implements RequestAwareInterface
{
    use RequestAwareController;

    /**
     * @return array<string, mixed>
     */
    #[Route(name: 'cache_info', path: '/cache/info', methods: ['GET'])]
    public function cacheInfo(): array
    {
        return apcu_cache_info();
    }

    /**
     * @return array{success: bool}
     */
    #[Route(name: 'cache_clear', path: '/cache/clear', methods: ['POST'])]
    public function cacheClear(): array
    {
        if (apcu_clear_cache()) {
            return ['success' => true];
        }

        return ['success' => false];
    }
}
