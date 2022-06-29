<?php

define('MODX_API_MODE', true);
define('IN_MANAGER_MODE', true);

require_once '../../../index.php';
$modx->db->connect();
$modx->getSettings();

require_once '__autoload.php';

echo (new VueManager\Application())->run();
