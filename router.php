<?php
require_once 'config.php';
require_once 'php/Path.class.php';

function try_file($request) {
	$file = Path::GetFullPath($request);
	if (!is_file($file)) {
		$file = Path::Combine(Config::GetImageDir(), $request);
		if (!is_file($file)) {
			require_once '/index.php';
			return true;
		}
	}

	if (preg_match("/.php$/i", $file))
		return false;

	header("Content-Type: " . mimetype($file));
	readfile($file);
	return true;
}

function mimetype($file) {
	preg_match("/\.([a-z0-9]+)$/i", $file, $ext);
	$ext = $ext[1];

	switch (strtolower($ext)) {
		case "php":
		case "htm":
		case "html":
			return "text/html";
		case "js":
			return "application/x-javascript";
		case "css":
			return "text/css";
		case "png":
			return "image/png";
		case "jpg":
		case "jpeg":
			return "image/jpeg";
		case "gif":
			return "image/gif";
		case "ico":
			return "application/x-icon";
		default:
			return "unknown/" . strtolower($ext);
	}
}

return try_file($_SERVER["SCRIPT_NAME"]);
