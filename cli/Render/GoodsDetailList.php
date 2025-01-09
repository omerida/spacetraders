<?php

namespace Phparch\SpaceTradersCLI\Render;

use Phparch\SpaceTraders\Value\GoodsDetail;

class GoodsDetailList extends AbstractRenderer
{
    public function __construct(
        /** @param GoodsDetail[] */
        public array $goods,
    )
    {
    }

    public function output(): string
    {
        $this->sprintf('<:CYN:>%-10s %-10s %-s', "Symbol", "Name", "Description");
        foreach ($this->goods as $good) {
            $this->sprintf(
                '%-10s %-10s %-s',
                $good->symbol->value,
                $good->name,
                $good->description
            );
        }
        return parent::output();
    }
}
