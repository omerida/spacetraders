<?php

namespace Phparch\SpaceTraders\Controller;

use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Interface\RequestAware;
use Phparch\SpaceTraders\Interface\TwigAware;
use Phparch\SpaceTraders\Repository;
use Psr\Http\Message\ResponseInterface;

class TradeController implements RequestAware, TwigAware
{
    use Trait\RequestAwareController;
    use Trait\TwigAwareController;

    public function __construct(
        private readonly Repository\MarketTradeGoodsActivity $marketRepo,
    ) {
    }

    #[Route(
        name: 'trade_home',
        path: '/trade',
        methods: ['GET'],
        strategy: 'application'
    )]
    public function tradeHome(): ResponseInterface
    {
        // This may get overwhelming fast
        $goods = $this->marketRepo->getAllLatest();

        return $this->render('trade/home.html.twig', [
            'goods' => $goods,
        ]);
    }
}
