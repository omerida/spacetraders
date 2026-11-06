<?php

require __DIR__ . '/../vendor/autoload.php';

use Revolt\EventLoop;


EventLoop::repeat(5, function (string $id): void {
    static $time = 0;

    echo "++ $time Executing callback created by EventLoop::repeat()" . PHP_EOL;

    $time += 5;
});


// What to do when a SIGINT signal is received
EventLoop::onSignal(SIGINT, function (): void {
    echo "Caught SIGINT! exiting ...\n";
    exit;
});

EventLoop::run();

