<?php

namespace Phparch\SpaceTraders\Controller;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTradersRest\Client;
use Phparch\SpaceTraders\Controller\Trait\RequestAwareController;
use Phparch\SpaceTraders\Controller\Trait\TwigAwareController;
use Phparch\SpaceTraders\Interface\RequestAware;
use Phparch\SpaceTraders\Interface\TwigAware;
use Phparch\SpaceTradersRest\Value\Market\TradeGoods;
use Phparch\SpaceTradersRest\Value\TradegoodType;
use Phparch\SpaceTradersRest\Value\Waypoint;
use Psr\Http\Message\ResponseInterface;

class MarketController implements RequestAware, TwigAware
{
    use RequestAwareController;
    use TwigAwareController;

    public function __construct(
        private readonly Client\Systems $client,
    ) {
    }

    /**
     * @throws BadRequestException
     */
    #[Route(
        name: 'systems_market',
        path: '/systems/market',
        methods: ['GET'],
        strategy: 'application'
    )]
    public function systemsMarket(): ResponseInterface
    {
        $query = $this->getRequest()->getQueryParams();
        $id = $query['id'] ?? null;

        if (!$id || !is_string($id)) {
            throw new BadRequestException("Waypoint ID GET param missing");
        }

        $id = strtoupper($id);
        if (!preg_match('/[A-Z0-9\-]+/', $id)) {
            throw new BadRequestException("Invalid characters in waypoint ID");
        }

        $point = new Waypoint\Symbol($id);

        $market = $this->client->market(
            system: $point->system,
            waypoint: $point->waypoint
        );

        //@todo - this should be in the client package
        if ($market->imports) {
            // sort by name
            usort($market->imports, fn($a, $b) => $a->name <=> $b->name);
        }

        if ($market->exports) {
            // sort by name
            usort($market->exports, fn($a, $b) => $a->name <=> $b->name);
        }

        if ($market->exchange) {
            // sort by name
            usort($market->exchange, fn($a, $b) => $a->name <=> $b->name);
        }

        if ($market->tradeGoods) {
            // Sort trade good by type and symbol
            usort(
                $market->tradeGoods,
                function (TradeGoods $a, TradeGoods $b) {
                    if ($a->type === $b->type) {
                        return $a->symbol->name <=> $b->symbol->name;
                    }

                    return $a->type->value <=> $b->type->value;
                }
            );

            $imports_tg = array_filter(
                $market->tradeGoods,
                fn($a) => $a->type === TradegoodType::IMPORT
            );
            $exports_tg = array_filter(
                $market->tradeGoods,
                fn($a) => $a->type === TradegoodType::EXPORT
            );
            $exchanges_tg = array_filter(
                $market->tradeGoods,
                fn($a) => $a->type === TradegoodType::EXCHANGE
            );
        }

        return $this->render('systems/market.html.twig', [
            'headTitle' => 'Market ' . $market->symbol,
            'symbol' => $market->symbol,
            'exports' => $market->exports,
            'imports' => $market->imports,
            'exchange' => $market->exchange,
            'transactions' => $market->transactions,
            'tradeGoods' => $market->tradeGoods,
            'importDetails' => $imports_tg ?? [],
            'exchangeDetails' => $exchanges_tg ?? [],
            'exportDetails' => $exports_tg ?? [],
        ]);
    }
}
