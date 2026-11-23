<?php

namespace Phparch\SpaceTraders\Automation;

use Phparch\SpaceTraders\Repository;
use Phparch\SpaceTraders\Automation\Executor\OrderFactory;
use Revolt\EventLoop;

class Watcher
{
    public function __construct(
        private Repository\Ship\OrderQueue $orderQueue,
        private OrderFactory $orderFactory,
    ) {
    }

    public function run(): void {
        $queues = $this->orderQueue->withOrdersPending();
        foreach ($queues as $queue) {
            $current = $queue->current;
            $order2exec = $queue->orders->get($current);

            if ($order2exec) {
                EventLoop::queue(function () use ($order2exec, $queue) {
                    $executor = $this->orderFactory->createForShipOrder($order2exec);
                    $executor->execute();

                    if ($executor->shouldAdvance()) {
                        $queue->advanceCurrent();
                    }
                    $queue->nextCheck = $executor->getNextCheck();
                    $this->orderQueue->save($queue, flush: true);
                });
            }
        }
    }

    public function __invoke(): void {
        $this->run();
    }
}
