<!DOCTYPE html>
<?php
include 'php/path.php';

if (is_file(Path::GetFullPath($_SERVER["SCRIPT_NAME"])))
	return false;
else
	require_once '/index.php';