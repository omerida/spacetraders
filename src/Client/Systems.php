<?php

namespace Phparch\SpaceTraders\Client;

use Phparch\SpaceTraders\Response;

class Systems extends \Phparch\SpaceTraders\Client
{
    public function market(string $system, string $waypoint)
    {
        $response = $this->get(
            sprintf('systems/%s/waypoints/%s/market', $system, $waypoint)
        );

        return $this->convertResponse($response, \Phparch\SpaceTraders\Value\Market::class);
    }

    public function shipyard(string $system, string $waypoint)
    {
        $response = $this->get(
            sprintf('systems/%s/waypoints/%s/shipyard', $system, $waypoint)
        );

        return $this->convertResponse($response, \Phparch\SpaceTraders\Value\Shipyard::class);
    }

    public function systemLocation(string $system, string $waypoint)
    {
        $response = $this->get(
            sprintf('systems/%s/waypoints/%s', $system, $waypoint)
        );

        return $this->convertResponse($response, Response\Systems\Waypoint::class);
    }

    public function waypoints(string $system, array $queryParams = [])
    {
        $url = sprintf('systems/%s/waypoints', $system);
        if ($queryParams) {
            $url .= '?' . http_build_query($queryParams);
        }
        $response = $this->get($url);
        return $this->convertResponse($response, Response\Systems\Waypoints::class);
    }
}
