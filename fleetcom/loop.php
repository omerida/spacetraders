<?php

require __DIR__ . '/../vendor/autoload.php';

use Phparch\SpaceTraders\ServiceContainer;
use Revolt\EventLoop;

require __DIR__ . '/../bootstrap.php';

$watcher = ServiceContainer::get(\Phparch\SpaceTraders\Automation\Watcher::class);

EventLoop::repeat(5, function() use ($watcher) {
    echo date(\DateTime::RFC2822) . "\n";
    $watcher->run();
});

// What to do when a SIGINT signal is received
EventLoop::onSignal( UV::SIGINT, function (): void {
    echo "Caught SIGINT! exiting ...\n";
    exit;
});

EventLoop::run();

