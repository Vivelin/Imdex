<?php
class Imdex
{
	private $basedir;

	/**
	 * Initializes a new instance of the Imdex class.
	 */
	public function __construct($basedir) {
		$this->basedir = Path::GetFullPath($basedir);
	}

	/**
	 * Determines whether you can go up or not.
	 * @return bool False if the base directory is the current folder, otherwise true.
	 */
	public function CanGoUp() {
		return ($this->basedir !== getcwd());
	}

	/**
	 * Lists all subfolders in the current directory.
	 * @return array An array containing the basenames of subfolders.
	 */
	public function Folders() {
		$folders = glob($this->basedir . DIRECTORY_SEPARATOR . "*", GLOB_ONLYDIR);
		foreach ($folders as &$value) {
			$value = basename($value);
		}
		unset($value);
		return $folders;
	}
}