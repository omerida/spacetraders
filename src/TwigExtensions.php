<?php

namespace Phparch\SpaceTraders;

use Phparch\SpaceTraders\Value\WaypointSymbol;
use Twig\Attribute\AsTwigFunction;
use Twig\Attribute\AsTwigExtension;

class TwigExtensions
{
    #[AsTwigFunction('viewWaypointPath')]
    public static function viewWaypointPath(string $wp): string
    {
        return "/systems/waypoint?id=" . $wp;
    }

    #[AsTwigFunction('viewShipPath')]
    public static function viewShipPath(string $id): string
    {
        return "/ship/info?ship=" . $id;
    }

    #[AsTwigFunction('viewShipCargo')]
    public static function shipLinkCargo(string $id, ?string $name): string
    {
        return "/ship/cargo?ship=" . $id;
    }

    #[AsTwigFunction('machine2readable')]
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
