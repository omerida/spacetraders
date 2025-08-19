<?php

namespace Phparch\SpaceTraders;

use Phparch\SpaceTraders\Value\WaypointSymbol;
use Twig\Attribute\AsTwigFilter;
use Twig\Attribute\AsTwigFunction;

class TwigExtensions
{
    #[AsTwigFunction('viewMarketplacePath')]
    public static function viewMarketplacePath(string $id): string
    {
        return "/systems/market?id=" . $id;
    }

    #[AsTwigFunction('viewShipCargoPath')]
    public static function viewShipCargoPath(string $id): string
    {
        return "/ship/cargo?ship=" . $id;
    }

    #[AsTwigFunction('viewShipPath')]
    public static function viewShipPath(string $id): string
    {
        return "/ship/info?ship=" . $id;
    }

    #[AsTwigFunction('viewShipyardPath')]
    public static function viewShipyardPath(string $id): string
    {
        return "/systems/shipyard?id=" . $id;
    }

    #[AsTwigFunction('viewWaypointPath')]
    public static function viewWaypointPath(string $wp): string
    {
        return "/systems/waypoint?id=" . $wp;
    }

    #[AsTwigFilter('machine2readable')]
    public static function machine2readable(string $input): string
    {
        // 1. Replace underscores with spaces
        $transformed = str_replace('_', ' ', $input);

        // 2. Uppercase the first letter of each word
        // ucwords works best if the string is first converted to lowercase
        // to ensure consistent capitalization (e.g., "ALUM_CORE" -> "Alum Core" not "ALUM Core")
        return ucwords(strtolower($transformed));
    }
}
