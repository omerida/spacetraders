<?php

namespace Phparch\SpaceTraders\Automation;

use Phparch\SpaceTraders\Repository;

class Watcher
{
    public function __construct(
        private Repository\Ship\OrderQueue $orderQueue,
    ) {
    }

    public function run(): void {
        $queues = $this->orderQueue->findAll();

        var_dump($queues);
    }

    public function __invoke(): void {
        $this->run();
    }
}
