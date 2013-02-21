<?php
spl_autoload_register(function ($class) {
    include 'php/' . $class . '.class.php';
});

/**
 * Deletes the specified file.
 *
 * @param string $fileName The name of the file to delete.
 * @return bool True if the file was deleted, otherwise false.
 */
function deleteFile($fileName) {
	if (!file_exists($fileName)) error("File does not exist.");
	if (!is_file($fileName)) error("Not a file.");

	return @unlink($fileName);
}

/**
 * Offers the specified file to the client for download.
 *
 * @param string $fileName The name of the file to download.
 */
function doDownload($fileName) {
	if (strpos($fileName, getcwd()) === FALSE)
		exit;

	$size = @getimagesize($fileName);
	$fp = @fopen($fileName, "rb");
	if ($size && $fp)
	{
	  header("Content-Type: {$size['mime']}");
	  header("Content-Length: " . filesize($fileName));
	  header("Content-Disposition: attachment; filename=\"" . basename($fileName) . "\"");
	  header("Content-Transfer-Encoding: binary");
	  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	  fpassthru($fp);
	}

	exit;
}

/**
 * Completes processing the request and returns an error.
 * 
 * @param string $message The message to display to the client.
 */
function error($message) {
	$json = json_encode(array(
        "error" => true,
        "message" => $message
    ));
    die($json);
}

// Handle the action
if (isset($_GET["action"])) {
	$action = $_GET["action"];
	switch ($action) {
	case "deleteFile":
		if (isset($_POST["filename"])) {
			$fileName = $_POST["filename"];
			deleteFile($fileName) or error("A problem occurred while deleting the file.");
		} else {
			error("No filename specified.");
		}
		break;
	case "download":
		if (isset($_GET["file"])) {
			$fileName = $_GET["file"];
			doDownload($fileName);
		} else {
			error("No filename specified.");
		}
		break;
	
	default:
		error("Invalid action specified.");
		break;
	}
} else {
	error("No action specified.");
}

// If we get here, it means we're done.
echo json_encode(array(
	"error" => false
));