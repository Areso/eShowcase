<?php
if (is_file('site-backend/config.php')) {
        require_once('site-backend/config.php');
}
//set charset
ini_set("default_charset",'utf-8');//utf-8 windows-1251
ini_set('display_errors', 1);
error_reporting('E_ALL & E_STRICT');

session_start();
$user_check=$_SESSION['login'];
$conn = mysqli_connect($DB_HOSTNAME, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE, $DB_PORT);

if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}
//echo $conn;
/* change character set to utf8 */
if (!mysqli_set_charset($conn, "utf8")) {
    //printf("Error loading character set utf8: %s\n", mysqli_error($conn));
    exit();
} else {
    //printf("Current character set: %s\n", mysqli_character_set_name($conn));
}

$query_line   = "select * from accounts where email='$user_check';";
$query        = mysqli_query($conn, $query_line) or die("Query error while checking account: ".mysqli_connect_error($conn).mysqli_errno($conn).mysqli_error($conn));;
$row2         = mysqli_fetch_array($query);
$verify       = $row2['is_blocked'];

$logtext          = "verify result ".$verify;
$file             = 'log.txt';
$logtext          = '\r\n'.$logtext;
file_put_contents($file, $logtext, FILE_APPEND | LOCK_EX);

if ($verify=1) {
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$thepos = strrpos($actual_link, "/");
	$theres = substr($actual_link, 0, $thepos);
	//backend
	$thepos = strrpos($theres, "/");
	$theres = substr($theres, 0, $thepos);
	$newURL = $theres.'/settings.php';
}		
?>
