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
		$path = preg_replace('!' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . '+!',
							 DIRECTORY_SEPARATOR, $path);
		if (substr($path, -1) === DIRECTORY_SEPARATOR)
			$path = substr($path, 0, -1);
		return $path;
	}

	/**
	 * Removes a query string from the end of the specified string.
	 * @param string @uri The string from which the query string is to be removed.
	 * @return string The string without query string.
	 */
	static function RemoveQueryString($uri) {
		$pos = strpos($uri, "?");
		if ($pos === FALSE)
			return urldecode($uri);

		$uri = substr($uri, 0, $pos);
		return urldecode($uri);
	}

	/**
	 * Returns the full, absolute path of a path. 
	 * @param string $path The path to make whole. This path may not exist, may be relative, and may 
	 * 	start with a slash.
	 * @return string The full, absolute path.
	 */
	static function GetFullPath($path) {
		$path = self::NormalizePath($path);
		if ((is_file($path) || is_dir($path) || is_link($path))
			&& dirname($path) !== "." 
			&& dirname($path) !== DIRECTORY_SEPARATOR)
			return $path;
		return self::NormalizePath(getcwd() . DIRECTORY_SEPARATOR . $path);
	}
}