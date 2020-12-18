#!/usr/bin/php
<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');
session_start();
include('testRabbitMQClient.php');
logErrors(error_get_last()["type"], error_get_last()["message"], error_get_last()["file"], error_get_last()["line"]);

$username = strtolower($_GET['username']);
$password = $_GET['password'];
if($username == '' || $password == '' || $username == null || $password == null){
	header("location:../loginPage.php?login=blank");
	$logText1 = " Login attempt failed due to null entry.\n";
	logLogin($logText1);
	exit();

}
$hashed = hash('sha512', $password);
$loginResponse = login($username, $hashed);

if($loginResponse == true){
	$_SESSION['username'] = $username;
	header("location:./homepage.php");
	$logText2 = "  Login attempt from User: $username | Login Successful.\n";
	logLogin($logText2);
}

elseif($loginResponse == false){
	header("location:../loginPage.php?login=failed");
	$logText3 = " Login attempt from User: $username | Failed due to incorrect credentials.\n";
	logLogin($logText3);
}


?>
