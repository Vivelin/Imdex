<?php
/**
*	Used for the database connection. Current setup is for MYSQL.
*	Comment the MySQL Setup out and restore the SQLite setup below.
*   Replace "users.db" with the path to the database file.
*	No other changes needs to be made.
*
*	Database structures:
*	Table name: users
*		id - int auto_increment (INTEGER PRIMARY KEY in SQLite)
*		username - varchar/text
*		password - varchar/text
*		sessionkey - varchar/text
*/

// START MYSQL SETUP
// $dbhost = "localhost"; // Address to the MySQL server. Usually localhost.
// $dbname = "login"; // Name of the database.
// $dbuser = "root"; // Username
// $dbpass = ""; // Password

// $db = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=UTF-8", $dbuser, $dbpass); // The connection object, don't edit.
// END MYSQL SETUP

// START SQLITE SETUP
$db = new PDO("sqlite:users.db"); 
// END SQLITE SETUP

?>