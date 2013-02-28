<?php
/*
*	Functions for checking, setting and removing sessions.
*/
require_once "connection.php";

/**
 * Checks if the user has a session. 
 *
 * @param The PDO database connection object.
 * @return An array containing userid and username if a session was found. Otherwise the  value false.
 */
function checkSession(&$db)
{
	$sessionid = filter_input(INPUT_COOKIE, "authid", FILTER_SANITIZE_SPECIAL_CHARS);
	$sessionkey = filter_input(INPUT_COOKIE, "authkey");	
	
	if(empty($sessionkey) || empty($sessionid))
	{
		return false;
	}
	
	$checksession = $db->prepare("SELECT * FROM users WHERE id=:id");
	$checksession->bindValue(":id", $sessionid, PDO::PARAM_INT);
	$checksession->execute();
	
	$user = $checksession->fetch(PDO::FETCH_ASSOC);
	
	if(empty($user))
	{
		resetSession($db);
		return false;
	}	
	
	if ($sessionkey == $user['sessionkey']) 
	{
		return array("id" => $user['id'], "username" => $user['username']);
	}
	else
	{
		resetSession($db, $user['id']);
		return false;
	}
}


/**
* Sets a session by creating a random key which is put in a cookie and in the database.
*
* @param The PDO database connection object.
*/
function setSession(&$db, $id)
{
	$length = 32;
	
	$rawkey = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	$sessionkey = crypt($rawkey);
	
	$updatesession = $db->prepare("UPDATE users SET sessionkey=:sessionkey WHERE id=:id");
	$updatesession->bindValue(":sessionkey", $sessionkey, PDO::PARAM_STR);
	$updatesession->bindValue(":id", $id, PDO::PARAM_INT);
	
	$updatesession->execute();
	
	if($updatesession->rowCount() == 1)
	{
		setcookie("authid", $id, time()+60*60*24*30, "/");
		setcookie("authkey", $sessionkey, time()+60*60*24*30, "/");
	}

}

/**
* Removes a session (loging out) by clearing cookies and if exists, the key from the database.
*
* @param The PDO database connection object.
* @param Userid.
*/
function resetSession(&$db, $id=-1)
{
	
	if($id >= 0)
	{
		$updatesession = $db->prepare("UPDATE users SET sessionkey=:sessionkey WHERE id=:id");
		$updatesession->bindValue(":sessionkey", "", PDO::PARAM_STR);
		$updatesession->bindValue(":id", $id, PDO::PARAM_INT);
	
		$updatesession->execute();
	}
	
	setcookie("authid", '', 0);
	setcookie("authkey", '', 0);
}

?>