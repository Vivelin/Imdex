<?php
include 'php/path.php';
$req = Path::GetFullPath($_SERVER["SCRIPT_NAME"]);
if (is_file($req))
	return false;
else
	require_once '/index.php';