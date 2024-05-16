<?php

namespace Phparch\SpaceTraders\Client;

use Phparch\SpaceTraders\Response;

class Systems extends \Phparch\SpaceTraders\Client
{
    public function systemLocation(string $system, string $waypoint)
    {
        $response = $this->get(
            sprintf('systems/%s/waypoints/%s', $system, $waypoint)
        );
        $json = $this->decodeResponse($response);

        return Response\Systems\Waypoints::fromArray($json['data']);
    }
}