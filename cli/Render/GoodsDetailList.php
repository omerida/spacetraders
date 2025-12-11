<?php

namespace Phparch\SpaceTradersCLI\Render;

use Phparch\SpaceTraders\Value\Goods\Detail;

class GoodsDetailList extends AbstractRenderer
{
    public function __construct(
        /** @var Detail[] $goods */
        public array $goods,
    )
    {
    }

    public function output(): string
    {
        $this->sprintf('<:CYN:>%-14s %-16s %-s', "Symbol", "Name", "Description");
        foreach ($this->goods as $good) {
            $this->sprintf(
                '<:GRN:>%-14s<:DEF:> <:YEL:>%-16s<:DEF:> %-s',
                $good->symbol->value,
                $good->name,
                $good->description
            );
        }
        return parent::output();
    }
}
