<?php
class Path
{
	/**
	 * Normalizes a path.
	 * @param string $path The path to normalize.
	 * @return string The normalized path. This path will only contain single backslashes,
	 * and won't end in a backslash.
	 */
	static function NormalizePath($path) {
		$path = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $path);
		$path = preg_replace('/' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . '+/',
							 DIRECTORY_SEPARATOR, $path);
		if (substr($path, -1) === DIRECTORY_SEPARATOR)
			$path = substr($path, 0, -1);
		return $path;
	}

	static function RemoveQueryString($uri) {
		$queryStringPos = strpos($uri, "?");
		$uri = substr($uri, 0, $queryStringPos);
		return $uri;
	}

	/**
	 * Returns the full, absolute path of a path. 
	 * @param string $path The path to make whole. This path may not exist, may be relative, and may 
	 * 	start with a slash.
	 * @return string The full, absolute path.
	 */
	static function GetFullPath($path) {
		$path = normalizePath($path);
		if (realpath($path) === false) {
			return normalizePath(getcwd() . DIRECTORY_SEPARATOR . $path);
		} else {
			return realpath($path);
		}
	}
}