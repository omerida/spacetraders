<?php

namespace Phparch\SpaceTraders\Data;

use Doctrine\DBAL;
use Doctrine\DBAL\Exception;

class KeyValueStore
{
    /** @var array<string, string>  */
    private $cacheString = [];
    /** @var array<string, ?int>  */
    private $cacheInt = [];
    /** @var array<string, ?bool>  */
    private $cacheBool = [];

    public function __construct(
        private DBAL\Connection $dbconn,
        private readonly string $tablePrefix,
    ) {
    }

    public function getString(string $key): ?string {

        if (isset($this->cacheString[$key])) {
            return $this->cacheString[$key];
        }

        $result = $this->queryTable('text', $key);
        if ($value = $result->fetchOne()) {
            /** @var scalar $value */
            $this->cacheString[$key] = (string) $value;
            return (string) $value;
        }
        $this->cacheInt[$key] = null;
        return null;
    }

    public function getInt(string $key): ?int {
        if (isset($this->cacheInt[$key])) {
            return $this->cacheInt[$key];
        }
        $result = $this->queryTable('int', $key);
        if ($value = $result->fetchOne()) {
            /** @var scalar $value */
            $this->cacheInt[$key] = (int) $value;
            return (int) $value;
        }
        $this->cacheInt[$key] = null;
        return null;
    }

    public function getBool(string $key, ?bool $default = null): ?bool {
        if (isset($this->cacheBool[$key])) {
            return $this->cacheBool[$key];
        }
        $result = $this->queryTable('bool', $key);
        if ($value = $result->fetchOne()) {
            /** @var scalar $value */
            $this->cacheBool[$key] = (bool) $value;
            return (bool) $value;
        }
        $this->cacheBool[$key] = $default;
        return $default;
    }

    /**
     * @throws Exception
     */
    public function storeText(string $key, string $value): int {
        return $this->insertValue('text', $key, $value);
    }

    public function storeInt(string $key, int $value): int {
        return $this->insertValue('int', $key, $value);
    }

    public function storeBool(string $key, bool $value): int {
        return $this->insertValue('bool', $key, $value);
    }

    private function queryTable(string $type, string $key): DBAL\Result {
        return $this->dbconn->executeQuery(
            "SELECT val FROM `{$this->tablePrefix}_{$type}` WHERE `name` = :key",
            [strtolower($key)],
            [DBAL\ParameterType::STRING]
        );
    }

    /**
     * @param 'int'|'text'|'bool' $type
     * @throws Exception
    */
    public function insertValue(string $type, string $key, string|bool|int $value): int {
        $valueType = match ($type) {
            'text' => DBAL\ParameterType::STRING,
            'int' => DBAL\ParameterType::INTEGER,
            'bool' => DBAL\ParameterType::BOOLEAN,
        };
        return (int) $this->dbconn->executeStatement(
            <<<SQL
            INSERT INTO `{$this->tablePrefix}_{$type}` 
                VALUES (?, ?, datetime('now'), datetime('now'))
                ON CONFLICT(`name`)
                   DO UPDATE SET `val` = ?, updated_at=datetime('now') WHERE `name` = ?
            SQL,
            [
                strtolower($key), $value, // INSERT
                $value, strtolower($key) // UPDATE
            ],
            [DBAL\ParameterType::STRING, $valueType, DBAL\ParameterType::STRING, $valueType]
        );
    }
}
