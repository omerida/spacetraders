<?php

namespace Phparch\SpaceTradersCLI\Render;

use Phparch\SpaceTraders\Value\Market\TradeGoods;

class TradeGoodsList extends AbstractRenderer
{
    public function __construct(
        /** @var TradeGoods[] */
        public array $goods,
    )
    {
    }

    public function output(): string
    {
        $this->sprintf(
            '<:CYN:>%-12s %-8s %13s   %-10s %10s %10s',
            "Symbol",
            "Type",
            "Trade Vol.",
            "Supply",
            //            "Activity",
            "Purchase",
            "Sell"
        );
        foreach ($this->goods as $good) {
            $this->sprintf(
                '%-12s %-8s %13s   %-10s %10s %10s',
                $good->symbol->value,
                $good->type->value,
                $good->tradeVolume,
                $good->supply->value,
                //                $good->activity->value,
                $good->purchasePrice,
                $good->sellPrice
            );
        }
        return parent::output();
    }
}
