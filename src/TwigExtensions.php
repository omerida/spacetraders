<?php

namespace Phparch\SpaceTraders;

use Phparch\SpaceTraders\Value\WaypointSymbol;
use Twig\Attribute\AsTwigFunction;

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
}
