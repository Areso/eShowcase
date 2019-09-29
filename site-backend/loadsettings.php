<?php
//load session
//require_once('lock.php');
session_start();
$login_session=$_SESSION['login'];


//load config
require_once('config.php');
//init env
//echo $DB_USERNAME;
ini_set("default_charset","utf-8");
ini_set("display_errors", 1);
set_time_limit(600);
//lets parse input

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

$query_line       = "select * from accounts where email='$login_session';";
$query            = mysqli_query($conn, $query_line) or die("Query error while getting account ID: ".mysqli_connect_error($conn).mysqli_errno($conn).mysqli_error($conn));
$row2             = mysqli_fetch_array($query);
$fullname         = $row2['fullname'];
$phonenumber      = $row2['phone_number'];
$is_blocked       = $row2['is_blocked'];

$stringtosend = "";
$stringtosend = $stringtosend."&fullname=".$fullname; 
$stringtosend = $stringtosend."&phonenumber=".$phonenumber;
$stringtosend = $stringtosend."&is_blocked=".$is_blocked;
$logtext      = "login_session is ".$login_session." and query is "."\r\n".$query_line."\r\n".$stringtosend;
$file         = 'log.txt';
file_put_contents($file, $logtext, FILE_APPEND | LOCK_EX);
echo $stringtosend;
?>
