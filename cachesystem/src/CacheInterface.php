<?php

namespace IvanHunko\CacheSystem;

/**
 * Interface CacheInterface
 */
interface CacheInterface
{
    public function get(string $key);

    public function set(string $key, $value, $ttl = 3600);

    public function deleteTrash();
}
