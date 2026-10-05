<?php
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
// Load .env configuration if present
$_env_file = file_exists(__DIR__ . '/../../.env') ? __DIR__ . '/../../.env' : (file_exists(__DIR__ . '/../.env') ? __DIR__ . '/../.env' : (file_exists(__DIR__ . '/.env') ? __DIR__ . '/.env' : ''));
if (!empty($_env_file) && is_readable($_env_file)) {
    $_env_lines = file($_env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($_env_lines as $_line) {
        $_line = trim($_line);
        if ($_line === '' || strpos($_line, '#') === 0) continue;
        if (strpos($_line, '=') !== false) {
            list($_k, $_v) = explode('=', $_line, 2);
            $_k = trim($_k);
            $_v = trim($_v, " \t\n\r\0\x0B\"'");
            if (getenv($_k) === false) {
                putenv("$_k=$_v");
                $_ENV[$_k] = $_v;
                $_SERVER[$_k] = $_v;
            }
        }
    }
}
//--------------------------------------------------------------
// Global Configuration File
//--------------------------------------------------------------

// SOME GUIDELINES:
// Make sure that all files/dirs have a valid permissions.
// Right permissions should be for dirs 0755 and for files 0644.
// Please mention in the below line which dirs/files must have writeable by webserver.
// You should also write some description here about dirs/files.
// This may be require to other developers who will might have to work on this project.

// <MENTION HERE DIRS/FILES NAME WHICH MUST HAVE WRITEABLE BY WEBSERVER>


       $HOST_NAME                        = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'uat.test.jnnjhansi.com';
        $DOCUMENT_ROOT                    = !empty($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/' : rtrim(dirname(__DIR__, 2), '/\\') . '/';

        //--------------------------------------------------------------
        // DATABASE PARAMETERS
        //--------------------------------------------------------------

        $DB_HOST                        = getenv('DB_HOST') ?: "localhost";                  // Database Host Server
        $DB_USERNAME                    = getenv('DB_USERNAME') ?: "jnnjhrqb_pis1";              // Database Username             
        $DB_PASSWORD                    = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : "pis1!@#";              // Password for the Db User             
        $DB_NAME                        = getenv('DB_DATABASE') ?: (getenv('DB_NAME') ?: "jnnjhrqb_pis1");              // Database name             
        $DB_REPORT_ERROR                = false;                        // To Report Error
        $DB_PERSISTENT_CONN             = false;                        // If Db Connection to be persistent
        //Application directory Path of the app from Virtual root
        //$MAP_VROOT_PATH                 = "";

        $TEMPLATE_DIR                   = "templates";

?>