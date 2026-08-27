<?php

namespace Phparch\SpaceTraders\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Phparch\SpaceTradersRest\Value\Goods;
use Phparch\SpaceTradersRest\Value\TradegoodType;
use Phparch\SpaceTradersRest\Value\Waypoint;
use Phparch\SpaceTraders\Doctrine\Type\WaypointSymbolType;

#[ORM\Entity()]
#[ORM\Table(name: 'market_trade_goods_activity')]
class MarketTradeGoodsActivity
{
    /**
     * @readonly
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id;

    public function __construct(
        #[ORM\Column(type: WaypointSymbolType::NAME, length: 256)]
        public readonly Waypoint\Symbol $waypointSymbol,
        #[ORM\Column(type: Types::STRING, length: 256, enumType: Goods\Symbol::class)]
        public readonly Goods\Symbol $symbol,
        #[ORM\Column(type: Types::STRING, length: 256, enumType: TradegoodType::class)]
        public readonly TradegoodType $type,
        #[ORM\Column(type: Types::STRING, length: 256, enumType: Goods\SupplyLevel::class)]
        public readonly Goods\SupplyLevel $supply,
        #[ORM\Column(type: Types::STRING, length: 256, enumType: Goods\TradeActivityLevel::class)]
        public readonly ?Goods\TradeActivityLevel $activity,
        #[ORM\Column(type: Types::INTEGER)]
        public readonly int $tradeVolume,
        #[ORM\Column(type: Types::INTEGER)]
        public readonly int $purchasePrice,
        #[ORM\Column(type: Types::INTEGER)]
        public readonly int $sellPrice,
        #[ORM\Column(type: Types::DATE_IMMUTABLE)]
        public private(set) \DateTimeImmutable $timestamp {
            get {
                return $this->timestamp;
            }
            set {
                // We could get a lot of these readings while playing the game,
                // and they may not change very often. For now, set the
                // timestamp to be midnight and then check that only one copy of
                // a day's readings are saved.
                $this->timestamp = $value->setTime(0, 0, 0);
            }
        }
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param \Phparch\SpaceTradersRest\Value\Market\TradeGoods[] $tradeGoods
     * @return self[]
     */
    public static function fromTradeGoodsValue(
        Waypoint\Symbol $waypointSymbol,
        array $tradeGoods,
        \DateTimeImmutable $timestamp,
    ): array {
        return array_map(
            function($good) use ($timestamp, $waypointSymbol): self {
                return new MarketTradeGoodsActivity(
                    waypointSymbol: $waypointSymbol,
                    symbol: $good->symbol,
                    type: $good->type,
                    supply: $good->supply,
                    activity: $good->activity,
                    tradeVolume: $good->tradeVolume,
                    purchasePrice: $good->purchasePrice,
                    sellPrice: $good->sellPrice,
                    timestamp: $timestamp,
                );
            },
            $tradeGoods
        );
    }
}
