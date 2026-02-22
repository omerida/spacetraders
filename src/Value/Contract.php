<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Trait\MapFromArray;

final class Contract
{
    use MapFromArray;

    public function __construct(
        /** @var non-empty-string */
        public string $id {
            set {
                if (empty(trim($value))) {
                    throw new \InvalidArgumentException('id cannot be empty');
                }
                $this->id = $value;
            }
        },
        public Faction\Symbol $factionSymbol,
        public Contract\Type $type,
        public Contract\Terms $terms,
        public bool $accepted,
        public bool $fulfilled,
        public \DateTimeImmutable $expiration,
        public \DateTimeImmutable $deadlineToAccept,
    ) {
    }
}
