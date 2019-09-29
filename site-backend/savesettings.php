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

$fullname        = test_input($_POST["fullname"]);
$phonenumber     = test_input($_POST["phonenumber"]);

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

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

$login_session    = mysqli_real_escape_string($conn, $login_session);
$fullname         = mysqli_real_escape_string($conn, $fullname);
$phonenumber      = mysqli_real_escape_string($conn, $phonenumber);

$query_line       = "select * from accounts where email='$login_session';";
$query            = mysqli_query($conn, $query_line) or die("Query error while getting account ID: ".mysqli_connect_error($conn).mysqli_errno($conn).mysqli_error($conn));
$row2             = mysqli_fetch_array($query);
//echo "presets are following, id_company_fk of user account is ".$id_company_fk." and id_account is ".$id_account." and login session is ".$login_session;

//echo "update company";
$query_line       = "update accounts set fullname='$fullname', 
                                         phone_number='$phonenumber',
                                         date_of_lastUpdate=now() 
                                         WHERE email='$login_session';";

$logtext          = "login_session is ".$login_session." and query is "."\r\n".$query_line;
$file             = 'log.txt';
$logtext          = '\r\n'.$query_line;
file_put_contents($file, $logtext, FILE_APPEND | LOCK_EX);
$query            = mysqli_query($conn, $query_line) or die("Query error inserting new company: ".mysqli_connect_error($conn).mysqli_errno($conn).mysqli_error($conn));	

echo "OK";
?>
