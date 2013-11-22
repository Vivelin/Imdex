<?php
class Imdex
{
	private $ignoredFolders;
	private $basedir;
	private $folders;
	private $images;

	/**
	 * Initializes a new instance of the Imdex class.
	 */
	public function __construct($basedir) {
		$this->basedir = Path::GetFullPath($basedir);
		if (!file_exists($this->basedir))
			throw new Exception("Directory does not exist: {$this->basedir}");

		$this->ignoredFolders = array("/assets", "/php");
	}

	public function IsReal() {
		return is_dir($this->basedir);
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
	 * Returns the path of the current directory.
	 * @return string The path of the current directory.
	 */
	public function Path() {
		return $this->basedir;
	}

	/**
	 * The parent folder.
	 * @return mixed An Imdex object that represents the parent directory, or FALSE if you can't go
	 * up a directory.
	 */
	public function Parent() {
		if (!$this->CanGoUp())
			return FALSE;

		return new Imdex(dirname($this->basedir));
	}

	/**
	 * Determines whether you can go up or not.
	 * @return bool False if the base directory is the current folder, otherwise true.
	 */
	public function CanGoUp() {
		return ($this->basedir !== Config::GetImageDir());
	}

	/**
	 * Lists all subfolders in the current directory.
	 * @return array An array containing the basenames of subfolders.
	 */
	public function Folders() {
		if ($this->folders === NULL) {
			$this->folders = glob($this->basedir . DIRECTORY_SEPARATOR . "*", GLOB_ONLYDIR);
			foreach ($this->folders as $key => &$value) {
				if ($this->IsIgnoredFolder($value)) {
					unset($this->folders[$key]);
				} else {
					$value = basename($value);
				}
			}
			unset($value);
			sort($this->folders);
		}
		return $this->folders;
	}

	/**
	 * Lists all images in the current directory.
	 */
	public function Images() {
		if ($this->images === NULL) {
			$this->images = array_filter(glob($this->basedir . DIRECTORY_SEPARATOR . "*"), 
				"self::IsImage");

			usort($this->images, function ($a, $b) {
				return filemtime($a) < filemtime($b);
			});
			
			// foreach ($this->images as &$value) {
			// 	$value = basename($value);
			// }
			// unset($value);
		}
		return $this->images;
	}

	/**
	 * Determines whether this directory has images or not.
	 * @return bool True if the directory contains images, otherwise false
	 */
	public function HasImages() {
		return (count($this->Images()) > 0);
	}

	/**
	 * Determines whether this directory has subfolders or not.
	 * @return bool True if the directory contains subfolders, otherwise false
	 */
	public function HasFolders() {
		return (count($this->Folders()) > 0);
	}

	/**
	 * Determines whether this directory is empty or not.
	 * @return bool True if the directory does not contain images or subfolders.
	 */
	public function IsEmpty() {
		return !$this->HasImages() && !$this->HasFolders();
	}

	/**
	 * Determines whether the specified folder should be ignored.
	 * @return bool True if the folder is to be ignored, otherwise false.
	 */
	private function IsIgnoredFolder($path) {
		foreach ($this->ignoredFolders as $key => $value) {
			if (Path::NormalizePath($path) === Path::GetFullPath($value)) {
				return true;
			}
		}
		return false;
	}

	private function IsImage($path) {
		return (bool)preg_match("/.(png|jpe?g|gif|bmp)$/i", $path);
	}
}