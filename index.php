 <?php

ob_start();
session_start();
header('Content-Type: text/html; charset=utf-8');



error_reporting(E_ALL);
ini_set('display_errors', '0');

include_once('config/common.php');
include_once('config/autoloader.php');
include_once('core/query.class.php');
include_once('core/router.sys.php');




