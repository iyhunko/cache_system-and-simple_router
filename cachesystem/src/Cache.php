<?php

namespace IvanHunko\CacheSystem;

use IvanHunko\CacheSystem\CacheInterface;

/**
 * Class Cache
 */
class Cache implements CacheInterface
{
    /**
     * !!! Order of drivers is a priority of cache usage !!!
     *
     * this config var will be filled in the constructor
     * example: ['static' => \ClassName::class, ...] (file, memcached etc...)
     *
     * @var array
     */
    private $cacheDrivers = [];

    /**
     * Cache constructor.
     *
     * @param array $drivers
     */
    public function __construct(array $drivers)
    {
        foreach ($drivers as $key => $driverName) {
            if (class_exists($drivers[$key])) {
                $this->cacheDrivers[$driverName] = new $drivers[$key]();
            }
        }
    }

    /**
     * in destructor we delete outdated cache data from all drivers
     */
    function __destruct()
    {
        $this->deleteTrash();
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        foreach ($this->cacheDrivers as $cacheDriver) {
            $result = $cacheDriver->get($key);
            if ($result) {
                return $result;
            }
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
        foreach ($this->cacheDrivers as $cacheDriverKey => $cacheDriver) {
            $cacheDriver->set($key, $value, $ttl);
        }
    }

    /**
     * delete outdated cache data from all drivers
     */
    public function deleteTrash()
    {
        foreach ($this->cacheDrivers as $cacheDriverKey => $cacheDriver) {
            $this->cacheDrivers[$cacheDriverKey]->deleteTrash();
        }
    }
}
