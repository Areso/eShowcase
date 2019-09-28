<?php
session_start();
if(session_destroy())
{	
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$thepos = strrpos($actual_link, "/");
	$theres = substr($actual_link, 0, $thepos);
	//backend
	$thepos = strrpos($theres, "/");
	$theres = substr($theres, 0, $thepos);
	$newURL = $theres.'/index.php';
	header('Location: '.$newURL);
}
?>

