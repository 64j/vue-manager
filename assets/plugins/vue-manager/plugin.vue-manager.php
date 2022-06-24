<?php

require_once '__autoload.php';

$q = $_REQUEST['q'];

if (stripos($q, 'vue-manager/api')) {
    print_r($q);

    exit;
}
