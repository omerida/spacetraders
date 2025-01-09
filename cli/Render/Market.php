<?php

namespace Phparch\SpaceTradersCLI\Render;

use Phparch\SpaceTraders\Value;

class Market extends AbstractRenderer
{
    public function __construct(
        public Value\Market $market,
    )
    {
    }

    public function output(): string
    {
        $this->heading($this->market->symbol);
        $this->newline();

        if ($this->market->exports) {
            $this->heading("EXPORTS");
            $this->passthru(new GoodsDetailList($this->market->exports));
        }

        if ($this->market->imports) {
            $this->heading("IMPORTS");
            $this->passthru(new GoodsDetailList($this->market->imports));
        }

        if ($this->market->exchange) {
            $this->heading("EXCHANGE");
            $this->passthru(new GoodsDetailList($this->market->exchange));
        }

        if ($this->market->transactions) {
            $this->heading("TRANSACTIONS");
            $this->passthru(new TransactionsList($this->market->transactions));
        }

        if ($this->market->tradeGoods) {
            $this->heading("TRADE GOODS");
            $this->passthru(new TradeGoodsList($this->market->tradeGoods));
        }
        return parent::output();
    }
}
