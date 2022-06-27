<?php

spl_autoload_register(function ($class) {
    $file = __DIR__ . '/src/' . str_replace('VueManager\\', '', $class) . '.php';
    if (is_file($file) && is_readable($file)) {
        require $file;
    }
});
