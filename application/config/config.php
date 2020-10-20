<?php
define('SITE_PATH', realpath('.') . DIRECTORY_SEPARATOR); //site path
define('SITE_DIR', substr(SITE_PATH,strlen($_SERVER['DOCUMENT_ROOT']))); //site dir

//DB Connect
define('DB_USER', 'keh192_mvc');
define('DB_PASS', 'XT&e6p&i');
define('DB_HOST', 'localhost');
define('DB_NAME', 'keh192_mvc');