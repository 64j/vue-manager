<?php

define('MODX_API_MODE', true);
define('IN_MANAGER_MODE', true);

require_once '../../../index.php';

/** @var \DocumentParser $modx */
$modx->db->connect();
$modx->getSettings();

require_once '__autoload.php';

echo VueManager\Application::getInstance()
    ->run();
