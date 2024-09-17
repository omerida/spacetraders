<?php

namespace Phparch\SpaceTraders\Client;

use Phparch\SpaceTraders\Response;

class Systems extends \Phparch\SpaceTraders\Client
{
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

    public function waypoints(string $system, string $type = '')
    {
        $url = sprintf('systems/%s/waypoints', $system);
        if ($type) {
            $url .= '?' . http_build_query(['traits' => $type]);
        }
        $response = $this->get($url);
        return $this->convertResponse($response, Response\Systems\Waypoints::class);
    }
}