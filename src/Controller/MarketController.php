<?php

namespace Phparch\SpaceTraders\Controller;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTradersRest\Client;
use Phparch\SpaceTraders\Controller\Trait\RequestAwareController;
use Phparch\SpaceTraders\Controller\Trait\TwigAwareController;
use Phparch\SpaceTraders\Interface\RequestAware;
use Phparch\SpaceTraders\Interface\TwigAware;
use Phparch\SpaceTraders\Repository;
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
        private readonly Repository\MarketTradeGoodsActivity $marketRepo,
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
            usort($market->imports, fn($good1, $good2) => $good1->name <=> $good2->name);
        }

        if ($market->exports) {
            // sort by name
            usort($market->exports, fn($good1, $good2) => $good1->name <=> $good2->name);
        }

        if ($market->exchange) {
            // sort by name
            usort($market->exchange, fn($good1, $good2) => $good1->name <=> $good2->name);
        }

        if ($market->tradeGoods) {
            // Sort trade good by type and symbol
            usort(
                $market->tradeGoods,
                function (TradeGoods $good1, TradeGoods $good2) {
                    if ($good1->type === $good2->type) {
                        return $good1->symbol->name <=> $good2->symbol->name;
                    }

                    return $good1->type->value <=> $good2->type->value;
                }
            );

            $importsGoods = array_filter(
                $market->tradeGoods,
                fn($good) => $good->type === TradegoodType::IMPORT
            );
            $exportsGoods = array_filter(
                $market->tradeGoods,
                fn($good) => $good->type === TradegoodType::EXPORT
            );
            $exchangesGoods = array_filter(
                $market->tradeGoods,
                fn($good) => $good->type === TradegoodType::EXCHANGE
            );
        } elseif ($goods = $this->marketRepo->getLatestForWaypoint($market->symbol)) {
            // Show latest historical info
            $importsGoods = $exchangesGoods = $exportsGoods = [];
            foreach ($goods as $good) {
                switch ($good->type) {
                    case TradegoodType::IMPORT:
                        $importsGoods[] = $good;
                        break;

                    case TradegoodType::EXPORT:
                        $exportsGoods[] = $good;
                        break;

                    case TradegoodType::EXCHANGE:
                        $exchangesGoods[] = $good;
                        break;
                }
            }
            $tradeGoodMsg = 'As of ' . $goods[0]->timestamp->format('Y-m-d');
        }

        return $this->render('systems/market.html.twig', [
            'headTitle' => 'Market ' . $market->symbol,
            'symbol' => $market->symbol,
            'exports' => $market->exports,
            'imports' => $market->imports,
            'exchange' => $market->exchange,
            'transactions' => $market->transactions,
            'tradeGoods' => $market->tradeGoods,
            'importDetails' => $importsGoods ?? [],
            'exchangeDetails' => $exchangesGoods ?? [],
            'exportDetails' => $exportsGoods ?? [],
            'tgMessage' => $tradeGoodMsg ?? '',
        ]);
    }
}
