<?php
include 'php/Path.class.php';
$req = Path::GetFullPath($_SERVER["SCRIPT_NAME"]);
if (is_file($req))
	return false;
else
	require_once '/index.php';