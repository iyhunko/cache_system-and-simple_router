<?php

spl_autoload_register(function ($class) {
    $prefix = 'IvanHunko\CacheSystem\\';
    if (strpos($class, $prefix) !== false) {
        $class = substr($class, strlen($prefix));
    }
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $filePath = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . $class . '.php';
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});
