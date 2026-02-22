<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Phparch\SpaceTraders\Rector\NonEmptyStringToPromotedPropertyHook;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src'
    ])
    // uncomment to reach your current PHP version
//     ->withPhpSets(php85: true)
    ->withRules([
        NonEmptyStringToPromotedPropertyHook::class
    ])
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0)
;