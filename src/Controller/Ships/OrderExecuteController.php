<?php

namespace Phparch\SpaceTraders\Controller\Ships;

use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Automation\Executor;
use Phparch\SpaceTraders\Controller\Trait;
use Phparch\SpaceTraders\Interface;
use Phparch\SpaceTraders\Repository;
use Psr\Http\Message\ResponseInterface;

class OrderExecuteController implements Interface\RequestAware, Interface\TwigAware
{
    use Trait\RequestAwareController;
    use Trait\TwigAwareController;

    public function __construct(
        private Repository\Ship\Order $orderRepo,
        private Executor\OrderFactory $factory,
    ) {
    }

    #[Route(
        name: 'execute_ship_order',
        path: '/ship/order/execute',
        methods: ['POST'],
        strategy: 'application'
    )]
    public function execute(): ResponseInterface
    {
        /**
         * @var array{order?: string} $params
         */
        $params = $this->getRequest()->getParsedBody();
        $shipID = $params['symbol'] ?? null;
        if (!$shipID || !is_string($shipID)) {
            throw new \RuntimeException("Ship POST param missing");
        }

        $orderID = $params['id'] ?? null;
        if (!$orderID) {
            throw new \RuntimeException('Order ID not found');
        }
        // validate it exists
        $order = $this->orderRepo->load($orderID);

        $executor = $this->factory->createForShipOrder($order);
        $executor->execute();

        $executor->setTwigEnvironment($this->twig);
        return $executor->getResponse();
    }
}
