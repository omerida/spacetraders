<?php

namespace Phparch\SpaceTraders\Data;

use Doctrine\DBAL;

class SystemRegistry extends KeyValueStore
{
    public function __construct(
        private DBAL\Connection $dbconn,
    ) {
        parent::__construct(
            $this->dbconn,
            'registry'
        );
    }
}
