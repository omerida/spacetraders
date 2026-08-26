<?php

namespace Phparch\SpaceTraders\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Phparch\SpaceTradersRest\Value\Goods;
use Phparch\SpaceTradersRest\Value\TradegoodType;

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
        #[ORM\Column(type: Types::STRING, length: 256, enumType: Goods\Symbol::class)]
        private Goods\Symbol $symbol {
            get {
                return $this->symbol;
            }
            set {
                $this->symbol = $value;
            }
        },
        #[ORM\Column(type: Types::STRING, length: 256, enumType: TradegoodType::class)]
        private TradegoodType $type {
            get {
                return $this->type;
            }
            set {
                $this->type = $value;
            }
        },
        #[ORM\Column(type: Types::STRING, length: 256, enumType: Goods\SupplyLevel::class)]
        private Goods\SupplyLevel $supply {
            get {
                return $this->supply;
            }
            set {
                $this->supply = $value;
            }
        },
        #[ORM\Column(type: Types::STRING, length: 256, enumType: Goods\TradeActivityLevel::class)]
        private ?Goods\TradeActivityLevel $activity {
            get {
                return $this->activity;
            }
            set {
                $this->activity = $value;
            }
        },
        #[ORM\Column(type: Types::INTEGER)]
        private int $tradeVolume {
            get {
                return $this->tradeVolume;
            }
            set {
                $this->tradeVolume = $value;
            }
        },
        #[ORM\Column(type: Types::INTEGER)]
        private int $purchasePrice {
            get {
                return $this->purchasePrice;
            }
            set {
                $this->purchasePrice = $value;
            }
        },
        #[ORM\Column(type: Types::INTEGER)]
        private int $sellPrice {
            get {
                return $this->sellPrice;
            }
            set {
                $this->sellPrice = $value;
            }
        },
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param \Phparch\SpaceTradersRest\Value\Market\TradeGoods[] $tradeGoodsValue
     * @return self[]
     */
    public static function fromTradeGoodsValue(array $tradeGoods): array {
        return array_map(
            function($good): self {
                return new MarketTradeGoodsActivity(
                    symbol: $good->symbol,
                    type: $good->type,
                    supply: $good->supply,
                    activity: $good->activity,
                    tradeVolume: $good->tradeVolume,
                    purchasePrice: $good->purchasePrice,
                    sellPrice: $good->sellPrice
                );
            },
            $tradeGoods
        );
    }
}
