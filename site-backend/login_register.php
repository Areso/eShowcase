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

//if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = test_input($_POST["email"]);
  $password = test_input($_POST["password"]);
  $tos_accepted = test_input($_POST["tos_accepted"]);
//}
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
$email = strtolower($email);

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
$email = mysqli_real_escape_string($conn, $email);
$query_line = "SELECT 
 * 
 FROM accounts
 WHERE email = '".$email."'";

//echo $query_line."<br>";
//echo "debug ".isset($conn);
$query = mysqli_query($conn, $query_line) or die("Query error while checking EMAIL: ".mysqli_connect_error($conn).mysqli_errno($conn).mysqli_error($conn));

$field = mysqli_field_count($conn);
//echo $field."<br>";
// create line with field names
//create 
// loop through database query and fill export variable
echo 'all is good';
$account_found = false;
while($row = mysqli_fetch_array($query)) {
	for($i = 0; $i < $field; $i++) {
		if ($i==2) {
			$pass_from_db = $row[mysqli_fetch_field_direct($query, $i)->name];
			$account_found = true;
			//echo 'account is found!';
		}
	}	
}

$password = mysqli_real_escape_string($conn, $password);

if ($account_found == false) {
	$allow_account_creation = true;
	$error_while_account_creation = "";
	if ($password=="") {
		//echo "password is null";
		$allow_account_creation = false;
		$error_while_account_creation = $error_while_account_creation."?p=0";
	}
	if ($tos_accepted==false) {
		$allow_account_creation = false;
		$error_while_account_creation = $error_while_account_creation."?t=0";
	}
	if ($allow_account_creation==true) {
		$query_line = "INSERT INTO 
		 accounts (email, passwordh, date_of_reg) VALUES 
		 ('".$email."','".md5($password)."', now());";
		echo ' query line is '.$query_line;
		$result = mysqli_query($conn, $query_line) or die("Query error while inserting new acc: ".mysqli_error()).' query line is '.$query_line;
		
		session_start();
		$_SESSION['login']   = $email;
		//echo "new account created";
		$file           = 'etc/log.txt';
		$logtext        = 'redirect to settings.php';
		file_put_contents($file, $logtext, FILE_APPEND | LOCK_EX);
		$_SESSION['login']   = $email;
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$thepos = strrpos($actual_link, "/");
		$theres = substr($actual_link, 0, $thepos);
		//backend
		$thepos = strrpos($theres, "/");
		$theres = substr($theres, 0, $thepos);
		$newURL = $theres.'/settings.php';
		header('Location: '.$newURL);
		/*
		$stmt = mysqli_prepare($link, $query);
		mysqli_stmt_bind_param($stmt, "sss", $val1, $val2, $val3);
		$val1 = 'abc';
		$val2 = 'cde';
		$val3 = 'qwe';
		mysqli_stmt_execute($stmt);
		*/
		//session start, redirect to char selection screen
	} else {
		//echo "new account not created";
		$newURL = '/login.php'.$error_while_account_creation;
		header('Location: '.$newURL);
		//todo redirect with parameters and parsing parameters in JS in HTML page
	}
}
if ($account_found == true) {
  if (md5($password) == $pass_from_db) {
	    //echo "login in existing account";
	    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		echo $actual_link;
		$file           = 'etc/log.txt';
		$logtext        = 'redirect to settings.php';
		file_put_contents($file, $actual_link, FILE_APPEND | LOCK_EX);
		session_start();
		$_SESSION['login']   = $email;
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$thepos = strrpos($actual_link, "/");
		$theres = substr($actual_link, 0, $thepos);
		//backend
		$thepos = strrpos($theres, "/");
		$theres = substr($theres, 0, $thepos);
		$newURL = $theres.'/goods.php';
		//new login data
		$query_line = "UPDATE accounts SET date_of_lastLogin=now() WHERE email='".$email."';";
		$file             = 'log.txt';
		$logtext          = '\r\n'.$query_line;
		file_put_contents($file, $logtext, FILE_APPEND | LOCK_EX);
		$result = mysqli_query($conn, $query_line) or die("Query while update lastLogin: ".mysqli_error()).' query line is '.$query_line;
		//
		header('Location: '.$newURL);
  } else {
	//echo "wrong pass for existing account";
	$error_wrong_password = "?wp=0"; //"wrong password! Try again or Restore the password";
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$thepos = strrpos($actual_link, "/");
	$theres = substr($actual_link, 0, $thepos);
	//backend
	$thepos = strrpos($theres, "/");
	$theres = substr($theres, 0, $thepos);
	$newURL = $theres.'/login.php'.$error_wrong_password;
	header('Location: '.$newURL);
	//todo redirect with parameters and parsing parameters in JS in HTML page
  }
}
?>
