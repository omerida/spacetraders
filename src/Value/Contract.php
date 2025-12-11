<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Contract\Terms;
use Phparch\SpaceTraders\Value\Contract\Type;
use Phparch\SpaceTraders\Value\Faction\Symbol;

final class Contract extends Base
{
    public function __construct(
        /** @var non-empty-string */
        public readonly string $id,
        public readonly Symbol $factionSymbol,
        public readonly Type $type,
        public readonly Terms $terms,
        public readonly bool $accepted,
        public readonly bool $fulfilled,
        public readonly \DateTimeImmutable $expiration,
        public readonly \DateTimeImmutable $deadlineToAccept,
    ) {
    }
}
