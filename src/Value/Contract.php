<?php

namespace Phparch\SpaceTraders\Value;

final class Contract
{
    public function __construct(
        /** @param non-empty-string */
        public readonly string $id,
        public readonly FactionSymbol $factionSymbol,
        public readonly string $type,
        public readonly ContractTerms $terms,
        public readonly bool $accepted,
        public readonly bool $fulfilled,
        public readonly \DateTimeImmutable $expiration,
        public readonly \DateTimeImmutable $deadlineToAccept,
    ) {}

}