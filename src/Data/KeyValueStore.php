<?php

namespace Phparch\SpaceTraders\Data;

use Doctrine\DBAL;
use Doctrine\DBAL\Exception;

class KeyValueStore
{
    /** @var array<string, string>  */
    private $cache_string = [];
    /** @var array<string, ?int>  */
    private $cache_int = [];
    /** @var array<string, ?bool>  */
    private $cache_bool = [];

    public function __construct(
        private DBAL\Connection $db,
        private readonly string $table_prefix,
    ) {
    }

    public function getString(string $key): ?string {

        if (isset($this->cache_string[$key])) {
            return $this->cache_string[$key];
        }

        $result = $this->queryTable('text', $key);
        if ($value = $result->fetchOne()) {
            /** @var scalar $value */
            $this->cache_string[$key] = (string) $value;
            return (string) $value;
        }
        $this->cache_int[$key] = null;
        return null;
    }

    public function getInt(string $key): ?int {
        if (isset($this->cache_int[$key])) {
            return $this->cache_int[$key];
        }
        $result = $this->queryTable('int', $key);
        if ($value = $result->fetchOne()) {
            /** @var scalar $value */
            $this->cache_int[$key] = (int) $value;
            return (int) $value;
        }
        $this->cache_int[$key] = null;
        return null;
    }

    public function getBool(string $key, ?bool $default = null): ?bool {
        if (isset($this->cache_bool[$key])) {
            return $this->cache_bool[$key];
        }
        $result = $this->queryTable('bool', $key);
        if ($value = $result->fetchOne()) {
            /** @var scalar $value */
            $this->cache_bool[$key] = (bool) $value;
            return (bool) $value;
        }
        $this->cache_bool[$key] = $default;
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
        return $this->db->executeQuery(
            "SELECT val FROM `{$this->table_prefix}_{$type}` WHERE `name` = :key",
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
        return (int) $this->db->executeStatement(
            <<<SQL
            INSERT INTO `{$this->table_prefix}_{$type}` 
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
