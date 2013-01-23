<!DOCTYPE html>
<?php
/**
 * Normalizes a path.
 * @param string $path The path to normalize.
 * @return string The normalized path. This path will only contain single backslashes,
 * and won't end in a backslash.
 */
function normalizePath($path) {
	$path = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $path);
	$path = preg_replace('/' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . '+/',
						 DIRECTORY_SEPARATOR, $path);
	if (substr($path, -1) === DIRECTORY_SEPARATOR)
		$path = substr($path, 0, -1);
	return $path;
}

/**
 * Returns the full, absolute path of a path. 
 * @param string $path The path to make whole. This path may not exist, may be relative, and may 
 * 	start with a slash.
 * @return string The full, absolute path.
 */
function getFullPath($path) {
	$path = normalizePath($path);
	if (realpath($path) === false) {
		return normalizePath(getcwd() . DIRECTORY_SEPARATOR . $path);
	} else {
		return realpath($path);
	}
}

include 'php/path.php';

if (is_file(Path::GetFullPath($_SERVER["SCRIPT_NAME"])))
	return false;
else
	require_once '/index.php';