<?php
if (is_file('config.php')) {
        require_once('config.php');
}
//echo 'result is '.$DB_USERNAME;
//$subnet = getenv("REMOTE_ADDR");!!!!!!!!!
//set charset
ini_set("default_charset",'utf-8');//utf-8 windows-1251
ini_set('display_errors', 1);
error_reporting('E_ALL & E_STRICT');
?>
