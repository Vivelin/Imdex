<?php
/*
*	Functions for logging in and out.
*	Use login.php?do=logout
*	For logging out (just like FP)
*/
require_once "php/session.php";
require_once "php/connection.php";

$action = filter_input(INPUT_GET, "do");

if(!isset($db))
{
	die("Couldn't get PDO!"); 
}

if($action == "logout")
{
	logout($db);
}
else
{	
	login($db);
}

/**
* Logs the user out (checks if logged in first) and redirects the user to index.
* Might want to change this.
*
* @param The PDO database connection object.
*/
function logout(&$db)
{
	$user = checkSession($db);
	if($user)
	{
		resetSession($db, $user['id']);
		echo "Logged out successfully, redirecting...";
	}
	else
	{
		echo "Not logged in, redirecting...";
	}
	
	echo "<meta http-equiv='REFRESH' content='3;url=index.php'>"; // Might want to change this.
}

/**
* Logs the user in. If already logged in, overwrites previous session.
* On successful login, redirects back to index. Might want to change this.
*
* @param The PDO database connection object.
*/
function login(&$db)
{
	$username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
	$password = filter_input(INPUT_POST, "password");

	if(empty($username) || empty($password))
	{
		die("Username or password empty!");
	}	

	$checklogin = $db->prepare("SELECT DISTINCT * FROM users WHERE username=:username");
	$checklogin->bindValue(":username", $username, PDO::PARAM_STR);
	$checklogin->execute();

	$user = $checklogin->fetch(PDO::FETCH_ASSOC);
	
	if(empty($user))
	{
		die("Wrong username or password!");
	}	

	if (crypt($password, $user['password']) == $user['password']) 
	{
		setSession($db, $user['id']);
		echo "Logged in successfully, redirecting...";
		echo "<meta http-equiv='REFRESH' content='3;url=index.php'>"; // Might want to change this.
	}
	else
	{
		die("Wrong username or password!");
	}
}
?>