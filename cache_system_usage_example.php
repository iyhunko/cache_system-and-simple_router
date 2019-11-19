<?php
//in the case of permission errors, please, make sure the correct permissions are set to let php create files
//setting correct user owner can fix permission error if it exists: # sudo chown -R www-data /var/www/your_project_folder
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'cachesystem/autoload.php';

use IvanHunko\CacheSystem\Cache;

$cache = new Cache([
    'file' => \IvanHunko\CacheSystem\FileCache::class,
    'static' => \IvanHunko\CacheSystem\StaticCache::class,
]);

echo $cache->get('to_be_cached21');
$cache->set('to_be_cached21', 'testvalue', 3);
echo $cache->get('to_be_cached21');