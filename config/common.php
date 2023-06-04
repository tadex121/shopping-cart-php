<?php

//--- ( CHANGE WHEN YOU PULL ON DEV/LIVE VERSION ) ------------------------------
$Production = FALSE;
//---------------------------------------------------------------------------------

if (empty($_SERVER['REMOTE_ADDR']) && !isset($_SERVER['HTTP_USER_AGENT'])) {
    define('CLI', 1);
} else {
    define('CLI', 0);
}


$SSL = TRUE;
define("PRODUCTION", $Production);

//--- ( BASIC ) --------------------------------------------------------------------
if (CLI == 1) {
    $SSL = FALSE;
}
if ($SSL) {
    define('PROTOCOL', "https://"); // LOCALHOST
    define('SERVER_NAME', $_SERVER['SERVER_NAME']); // LOCALHOST
} else {
    define('PROTOCOL', "http://"); // LOCALHOST
    define('SERVER_NAME', $_SERVER['SERVER_NAME']); // LOCALHOST
}

define('REVISION', time()); // REFRESH CSS, JS IF NECESSARY
define('PROJECT_NAME', '');
if (PROJECT_NAME != '') {
    define('PROJECT_PATH', '/' . PROJECT_NAME);
    $REQUEST_URL = str_replace("/" . PROJECT_NAME, "", $_SERVER['REQUEST_URI']);
    define('REQUEST_URI', $REQUEST_URL);
} else {
    define('PROJECT_PATH', '');
    define('REQUEST_URI', $_SERVER['REQUEST_URI']); // URI
}
define('URL', PROTOCOL . SERVER_NAME . REQUEST_URI);

//--- ( URL PATHS ) ---------------------------------------------------------------------------------
if (PROJECT_NAME != '') {
    define('BASE_URL', PROTOCOL . SERVER_NAME . '/' . PROJECT_NAME);
} else {
    define('BASE_URL', PROTOCOL . SERVER_NAME);
}
//echo BASE_URL;
define('PORTAL_URL', BASE_URL);
define('CORE_URL', BASE_URL . '/core');
define('HELPERS_URL', BASE_URL . '/application/helpers');
define('LIBRARY_URL', BASE_URL . '/library');
//echo BASE_URL ."<br />";
//--- ( DOCUMENT PATHS ) -------------------------------------------------------
if (CLI == 1) {
    define('DOCUMENT_ROOT', __DIR__ . '/..');
} elseif (PROJECT_NAME != '') {
    define('DOCUMENT_ROOT', $_SERVER["DOCUMENT_ROOT"] . "/" . PROJECT_NAME); // Windows: C:/wamp/www/
} else {
    define('DOCUMENT_ROOT', rtrim($_SERVER["DOCUMENT_ROOT"], '/'));
}


define('APP_PATH', DOCUMENT_ROOT . '/application');
define('CONTROLLERS_PATH', DOCUMENT_ROOT . '/application/controllers');
define('VIEWS_PATH', DOCUMENT_ROOT . '/application/views');
define('LIBRARY_PATH', DOCUMENT_ROOT . '/library');
define('APIS_PATH', DOCUMENT_ROOT . '/apis');
define('CORE_PATH', DOCUMENT_ROOT . '/core');
define('HELPERS_PATH', DOCUMENT_ROOT . '/application/helpers');
define('CONFIG_PATH', DOCUMENT_ROOT . '/config');
define('MODELS_PATH', DOCUMENT_ROOT . '/application/models');

//echo DOCUMENT_ROOT . "<br />";
//echo APIS_PATH . "<br />";
//--- ( USER ROLES ) ----------------------------------------------------------
define('ROLE_', '');

//--- ( DATABASE ) -------------------------------------------------------------
define('HOST', 'localhost');
define('DEFAULT_COMPANY_SCHEMA', '');
define('USERNAME', 'root');
define('PASSWORD', '');
define('DATABASE', 'root');

