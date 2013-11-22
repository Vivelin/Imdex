<?php
class Config
{
	// Full path to the directory containing images - leave empty to use current directory
	// private static $imageDir = "D:\\Dropbox\\Public";

	/* Don't touch the following unless you know what you're doing (but to be fair, if you're
	 * reading this, you probably do).
	 */
	static function GetImageDir() {
		if (isset(self::$imageDir) && is_dir(self::$imageDir))
			return self::$imageDir;
		return getcwd();
	}
}
