<?php

namespace IvanHunko\CacheSystem;

use IvanHunko\CacheSystem\CacheInterface;

/**
 * Class Cache
 */
class StaticCache implements CacheInterface
{
    private static $cacheData = [];

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        if (isset(self::$cacheData[$key]) && (self::$cacheData[$key]['time'] + self::$cacheData[$key]['ttl']) > time()) {
            return self::$cacheData[$key]['value'];
        }

        return null;
    }

    /**
     * @param string $key
     * @param $value
     * @param int $ttl
     */
    public function set(string $key, $value, $ttl = 3600)
    {
        self::$cacheData[$key] = [
            'value' => $value,
            'time' => time(),
            'ttl' => $ttl
        ];
    }

    /**
     * delete outdated cache data
     */
    public function deleteTrash()
    {
        $currentTime = time();
        foreach (self::$cacheData as $key => $item) {
            if ($currentTime >= ($item['time'] + $item['ttl'])) {
                unset(self::$cacheData[$key]);
            }
        }
    }
}
