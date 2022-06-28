<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

define('MODX_API_MODE', true);
define('IN_MANAGER_MODE', true);

require_once '../../../index.php';

require_once '__autoload.php';

$app = new VueManager\Application();

$app->run();

//$token = isset($_SERVER['HTTP_X_ACCESS_TOKEN']) ? base64_decode($_SERVER['HTTP_X_ACCESS_TOKEN']) : null;
//$body = $_POST + (json_decode(file_get_contents('php://input'), true) ?? []);
//$data = [];
//$meta = [];
//
//define('MODX_API_MODE', true);
//define('IN_MANAGER_MODE', true);
//
//require_once '../../../index.php';
//
///** @var DocumentParser $modx */
//$modx->db->connect();
//
//if (empty ($modx->config)) {
//    $modx->getSettings();
//}
//
//$modx->sid = session_id();
//$modx->loadExtension('ManagerAPI');
//
//$_lang = [];
//include MODX_MANAGER_PATH . '/includes/lang/english.inc.php';
//if ($modx->config['manager_language'] != 'english') {
//    include MODX_MANAGER_PATH . '/includes/lang/' . $modx->config['manager_language'] . '.inc.php';
//}
//include_once MODX_MANAGER_PATH . '/media/style/' . $modx->config['manager_theme'] . '/style.php';
//
//if ($token) {
//
//}
//
//
//if (!isset($_SESSION['mgrValidated'])) {
//    header('HTTP/1.1 401 Unauthorized');
//}
//
//header('Content-Type: application/json; charset=utf-8');
//
//print json_encode([
//    'meta' => $meta,
//    'data' => $data,
//], JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);
