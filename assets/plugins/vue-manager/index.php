<?php

define('MODX_API_MODE', true);
define('IN_MANAGER_MODE', true);

require_once '../../../index.php';

/** @var \DocumentParser $modx */
$modx->db->connect();
$modx->getSettings();

spl_autoload_register(function ($class) {
    $file = __DIR__ . '/src/' . str_replace(['VueManager\\', '\\'], ['', DIRECTORY_SEPARATOR], $class) . '.php';
    if (is_file($file) && is_readable($file)) {
        require $file;
    }
});

echo VueManager\Application::getInstance()
    ->run();
