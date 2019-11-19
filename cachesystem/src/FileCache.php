<?php

namespace IvanHunko\CacheSystem;

use IvanHunko\CacheSystem\CacheInterface;

/**
 * Class Cache
 */
class FileCache implements CacheInterface
{
    /**
     * to avoid multiply config file reading
     *
     * @var array
     */
    private $config;

    /**
     * file_path + file_name from config; for easier access
     *
     * @var string
     */
    private $file;

    /**
     * FileCache constructor.
     */
    public function __construct()
    {
        $this->file = $this->config('cache_file_path') . $this->config('cache_file_name');
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        if (file_exists($this->file)) {
            $cache = json_decode(file_get_contents($this->file), 1);
            if (isset($cache[$key]) && ($cache[$key]['time'] + $cache[$key]['ttl']) > time()) {
                return $cache[$key]['value'];
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
        if (file_exists($this->file)) {
            $cache = json_decode(file_get_contents($this->file), 1);
            $cache[$key] = [
                'value' => $value,
                'time' => time(),
                'ttl' => $ttl
            ];
        } else {
            $cache = [
                $key => [
                    'value' => $value,
                    'time' => time(),
                    'ttl' => $ttl
                ]
            ];
        }
        $serializedCacheItem = json_encode($cache);
        if (!file_exists($this->config('cache_file_path'))) {
            mkdir($this->config('cache_file_path'), 0777, true);
        }
        file_put_contents($this->file, $serializedCacheItem);
    }

    /**
     * delete outdated cache data
     */
    public function deleteTrash()
    {
        if (file_exists($this->file)) {
            $cache = json_decode(file_get_contents($this->file), 1);
            $currentTime = time();
            foreach ($cache as $key => $item) {
                if ($currentTime >= ($item['time'] + $item['ttl'])) {
                    unset($cache[$key]);
                }
            }
            $serializedCacheItem = json_encode($cache);
            file_put_contents($this->file, $serializedCacheItem);
        }
    }

    /**
     * config is saved in $this->config after first file loading
     *
     * @param $key
     *
     * @return |null
     */
    private function config($key)
    {
        if (!empty($this->config)) {
            if (isset($this->config[$key])) {
                return $this->config[$key];
            }
        } elseif (empty($this->config)) {
            $this->config = include 'config.php';
            if (isset($this->config[$key])) {
                return $this->config[$key];
            }
        }

        return null;
    }
}
