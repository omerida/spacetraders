<?php

namespace Phparch\SpaceTraders\Controller\Ships;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Interface;
use Phparch\SpaceTraders\Controller\Trait;
use Psr\Http\Message\ResponseInterface;
use Phparch\SpaceTraders\Repository;

class OrdersController implements Interface\RequestAware, Interface\TwigAware
{
    use Trait\RequestAwareController;
    use Trait\TwigAwareController;

    public function __construct(
        private Repository\Ship\OrderQueue $orderQueueRepo,
    ) {
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
        $queue = $this->orderQueueRepo->getForShip($shipID);

        return $this->render('ships/orders/new.html.twig', [
            'shipID' => $shipID,
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

    /**
     * @phpstan-ignore method.unused
     */
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
