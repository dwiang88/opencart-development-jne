<?php
// HTTP
define('HTTP_SERVER', 'http://localhost/opencart-development-jne/');

// HTTPS
define('HTTPS_SERVER', 'http://localhost/opencart-development-jne/');

// HTTP IMAGE
define('HTTP_IMAGE', HTTP_SERVER . 'image/');

// HTTPS IMAGE
define('HTTPS_IMAGE', HTTP_SERVER . 'image/');

// DIR
define('ROOT', dirname(__FILE__));
define('DIR_APPLICATION', ROOT . '/catalog/');
define('DIR_SYSTEM', ROOT . '/system/');
define('DIR_DATABASE', ROOT . '/system/database/');
define('DIR_LANGUAGE', ROOT . '/catalog/language/');
define('DIR_TEMPLATE', ROOT . '/catalog/view/theme/');
define('DIR_CONFIG', ROOT . '/system/config/');
define('DIR_IMAGE', ROOT . '/image/');
define('DIR_CACHE', ROOT . '/system/cache/');
define('DIR_DOWNLOAD', ROOT . '/download/');
define('DIR_LOGS', ROOT . '/system/logs/');

// define('DIR_APPLICATION', '/var/www/opencart-development-jne/catalog/');
// define('DIR_SYSTEM', '/var/www/opencart-development-jne/system/');
// define('DIR_DATABASE', '/var/www/opencart-development-jne/system/database/');
// define('DIR_LANGUAGE', '/var/www/opencart-development-jne/catalog/language/');
// define('DIR_TEMPLATE', '/var/www/opencart-development-jne/catalog/view/theme/');
// define('DIR_CONFIG', '/var/www/opencart-development-jne/system/config/');
// define('DIR_IMAGE', '/var/www/opencart-development-jne/image/');
// define('DIR_CACHE', '/var/www/opencart-development-jne/system/cache/');
// define('DIR_DOWNLOAD', '/var/www/opencart-development-jne/download/');
// define('DIR_LOGS', '/var/www/opencart-development-jne/system/logs/');

// DB
define('DB_DRIVER', 'mysql');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_DATABASE', ' opencart-development');
define('DB_PREFIX', 'oc_');
?>