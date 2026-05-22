<?php

namespace Phparch\SpaceTraders\Data;

use Doctrine\DBAL;

class SystemRegistry extends KeyValueStore
{
    public function __construct(
        private DBAL\Connection $db,
    ) {
        parent::__construct(
            $this->db,
            'registry'
        );
    }
}
