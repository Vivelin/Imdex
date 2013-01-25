<?php
class Imdex
{
	private $basedir;
	private $folders;
	private $images;

	/**
	 * Initializes a new instance of the Imdex class.
	 */
	public function __construct($basedir) {
		$this->basedir = Path::GetFullPath($basedir);
	}

	/**
	 * Returns the name of the current directory.
	 * @return string The basename of the current directory.
	 */
	public function Name() {
		$name = basename($this->basedir);		
		return $name;
	}

	/**
	 * The parent folder.
	 * @return mixed An Imdex object that represents the parent directory, or FALSE if you can't go
	 * up a directory.
	 */
	public function Parent() {
		if (!self::CanGoUp())
			return FALSE;

		return new Imdex(dirname($this->basedir));
	}

	/**
	 * Determines whether you can go up or not.
	 * @return bool False if the base directory is the current folder, otherwise true.
	 */
	public function CanGoUp() {
		return ($this->basedir !== getcwd());
	}

	/**
	 * Lists all subfolders with images in the current directory.
	 * @return array An array containing the basenames of subfolders.
	 */
	public function Folders() {
		if ($this->folders === NULL) {
			$this->folders = glob($this->basedir . DIRECTORY_SEPARATOR . "*", GLOB_ONLYDIR);
			foreach ($this->folders as &$value) {
				$value = basename($value);
			}
			unset($value);
		}
		return $this->folders;
	}

	/**
	 * Lists all images in the current directory.
	 */
	public function Images() {
		if ($this->images === NULL) {
			$this->images = glob($this->basedir . DIRECTORY_SEPARATOR . "*.{png,jpg,jpeg,gif}", GLOB_BRACE);
			foreach ($this->images as &$value) {
				$value = basename($value);
			}
			unset($value);
		}
		return $this->images;
	}

	/**
	 * Determines whether this directory has images or not.
	 * @return bool True if the directory contains images, otherwise false
	 */
	public function HasImages() {
		return (count(self::Images()) > 0);
	}
}