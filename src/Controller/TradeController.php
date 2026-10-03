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
    public function home(): ResponseInterface
    {
        // This may get overwhelming fast
        $goods = $this->marketRepo->getAllLatest();

        return $this->render('trade/home.html.twig', [
            'goods' => $goods,
        ]);
    }

    /**
     * @return array<mixed>
     */
    #[Route(
        name: 'trade_prices',
        path: '/trade/prices',
        methods: ['GET'],
        strategy: 'json'
    )]
    public function prices(): array
    {
        $query = $this->getRequest()->getQueryParams();

        return match ($query['mode']) {
            'all' => $this->marketRepo->getAll(),
            default => $this->marketRepo->getAllLatest(),
        };
    }
}
