<?php

declare(strict_types=1);

use Phparch\SpaceTraders\Rector\NonNegativeIntToPromotedPropertyHook;
use Phparch\SpaceTraders\Rector\NonEmptyStringToPromotedPropertyHook;
use Phparch\SpaceTraders\Rector\PositiveIntToPromotedPropertyHook;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src'
    ])
    // uncomment to reach your current PHP version
     //->withPhpSets(php84: true)
    ->withRules([
        NonEmptyStringToPromotedPropertyHook::class,
        NonNegativeIntToPromotedPropertyHook::class,
        PositiveIntToPromotedPropertyHook::class,
    ])
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0)
;