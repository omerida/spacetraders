<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Trait\MapFromArray;

final class Contract
{
    use MapFromArray;

    public function __construct(
        /** @var non-empty-string */
        public readonly string $id,
        public readonly Faction\Symbol $factionSymbol,
        public readonly Contract\Type $type,
        public readonly Contract\Terms $terms,
        public readonly bool $accepted,
        public readonly bool $fulfilled,
        public readonly \DateTimeImmutable $expiration,
        public readonly \DateTimeImmutable $deadlineToAccept,
    ) {
    }
}
