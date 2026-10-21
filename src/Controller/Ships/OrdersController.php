<?php

namespace Phparch\SpaceTraders\Controller\Ships;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Interface;
use Phparch\SpaceTraders\Controller\Trait;
use Psr\Http\Message\ResponseInterface;

class OrdersController implements Interface\RequestAware, Interface\TwigAware
{
    use Trait\RequestAwareController;
    use Trait\TwigAwareController;

    public function __construct() {
    }

    #[Route(
        name: 'new_ship_order',
        path: '/ship/order/new',
        methods: ['GET'],
        strategy: 'application'
    )]
    public function newOrder(): ResponseInterface
    {
        $shipID = $this->getShipID();

        return $this->render('ships/orders/new.html.twig', [
        ]);
    }


    private function getShipID(): string
    {
        $query = $this->getRequest()->getQueryParams();
        $id = $query['ship'] ?? null;

        if (!$id || !is_string($id)) {
            throw new BadRequestException("Ship GET param missing");
        }

        return $id;
    }

    private function getShipIdFromPost(): string
    {
        $post = (array) $this->getRequest()->getParsedBody();
        $id = $post['ship'] ?? null;

        if (!$id || !is_string($id)) {
            throw new BadRequestException("Ship POST param missing");
        }

        return $id;
    }
}
