<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Response\Base;

final class Contract extends Base
{
    public function __construct(
        /** @var non-empty-string */
        public readonly string $id,
        public readonly FactionSymbol $factionSymbol,
        public readonly ContractType $type,
        public readonly ContractTerms $terms,
        public readonly bool $accepted,
        public readonly bool $fulfilled,
        public readonly \DateTimeImmutable $expiration,
        public readonly \DateTimeImmutable $deadlineToAccept,
    ) {
    }
}
