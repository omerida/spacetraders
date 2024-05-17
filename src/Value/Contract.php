<?php

namespace Phparch\SpaceTraders\Value;

final class Contract
{
    public function __construct(
        /** @var non-empty-string */
        public readonly string $id,
        public readonly FactionSymbol $factionSymbol,
        public readonly string $type, // enum
        public readonly ContractTerms $terms,
        public readonly bool $accepted,
        public readonly bool $fulfilled,
        public readonly \DateTimeImmutable $expiration,
        public readonly \DateTimeImmutable $deadlineToAccept,
    ) {}

}