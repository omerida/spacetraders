<?php

namespace Phparch\SpaceTraders\Data;

use Doctrine\DBAL;

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

    private function queryTable(string $type, string $key): DBAL\Result {
        return $this->db->executeQuery(
            "SELECT val FROM `{$this->table_prefix}_{$type}` WHERE `name` = :key",
            [strtolower($key)],
            [DBAL\ParameterType::STRING]
        );
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

    public function store(string $key, mixed $value): void {
    }
}
