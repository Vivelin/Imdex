<?php
date_default_timezone_set('Europe/Amsterdam');
spl_autoload_register(function ($class) {
    include 'php/' . $class . '.class.php';
});

require_once 'config.php';
require_once "php/session.php";
require_once "php/connection.php";

/**
 * Prints the elements of the subfolder navigation list.
 *
 * @param Imdex $imdex 	The Imdex instance of the folder whose subfolders to print.
 * @param bool $isAdmin	True to display the navigation in management mode.
 */
function print_nav($imdex, $isAdmin) {
	if ($imdex->CanGoUp()) {
		$parent = htmlspecialchars($imdex->Parent()->Name());
		$url = "../";
		if ($isAdmin)
			$url .= "?manage";

		echo "\n\t\t\t\t<li><a href=\"{$url}\"><i class=\"icon-arrow-up\"></i> {$parent}</a>";
	}

	if ($imdex->CanGoUp() && $imdex->HasFolders())
		echo "\n\t\t\t\t<li class=\"divider\">";

	foreach ($imdex->Folders() as $value) {
		$sub = new Imdex($imdex->Path() . DIRECTORY_SEPARATOR . $value);
		$name = htmlspecialchars($value);
		$url = rawurlencode($value) . "/";
		if ($isAdmin)
			$url .= "?manage";

		echo "\n\t\t\t\t<li"; 
		if ($sub->IsEmpty())
			echo " class=\"disabled\"";
		echo "><a href=\"{$url}\"><i class=\"icon-caret-right\"></i> {$name}</a>";
	}
}

/**
 * Prints thumbnails for the specified files.
 *
 * @param array $files 	The files to print thumbnails for.
 * @param bool $isAdmin	True to display the thumbnails in management mode.
 */
function print_thumbs($files, $isAdmin) {
	$chunks = array_chunk($files, 3);
	foreach ($chunks as $row) {
		echo "\n\t\t\t<ul class=\"thumbnails\">";
		foreach ($row as $value) {
			print_thumb($value, $isAdmin);
		}
		echo "\n\t\t\t</ul>";
	}	
}

/**
 * Prints a single thumbnail for the specified file.
 *
 * @param string $image The file to print thumbnail for.
 * @param bool $isAdmin	True to display the thumbnail in management mode.
 */
function print_thumb($image, $isAdmin) {
	$name = htmlspecialchars(basename($image));
	$url = rawurlencode(basename($image));
	$qurl = urlencode($image);

	$date = htmlspecialchars(Pretty::RelativeTime(filemtime($image)));
	$fsize = htmlspecialchars(Pretty::FormatFileSize(filesize($image)));
	$size = getimagesize($image);

	if (!$isAdmin) {
		echo "\n\t\t\t\t<li class=\"span4\"><a href=\"{$url}\" class=\"thumbnail\">"
		   . "<img src=\"{$url}\" alt=\"{$name}\" title=\"{$name}\"></a>";
	} else {
		?> 
			<li class="span4">
				<div class="thumbnail">
					<img src="<?php echo $url;?>" alt="<?php echo $name;?>">
					<div class="caption">
						<h4 title="<?php echo $name;?>"><?php echo $name;?></h4>
						<p class="muted">
							Last updated <?php echo $date;?><br>
							<?php echo $size[0] . "x" . $size[1] . ", " . $fsize;?><br>
						</p>
						<button class="btn btn-danger" type="button" 
								onclick="deleteFile(<?php echo htmlspecialchars(json_encode($image));?>, this);">
							<i class="icon-trash"></i> Delete
						</button>
						<a class="btn" href="/do.php?action=download&file=<?php echo $qurl;?>"><i class="icon-download-alt"></i> Download</a>
					</div>
				</div>
			</li>
		<?php
	}
}


$user = checkSession($db);
$isAdmin = isset($_GET["manage"]) && ($user !== FALSE);

$requestDir = Path::RemoveQueryString($_SERVER["REQUEST_URI"]);
$path = Path::Combine(Config::GetImageDir(), $requestDir);
$imdex = new Imdex($path);

?>
<!DOCTYPE html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Images in <?php echo htmlspecialchars($imdex->Name());?></title>
<link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="/assets/css/font-awesome.min.css" rel="stylesheet" media="screen">
<link href="/assets/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
<link href="/assets/css/base.css" rel="stylesheet" media="screen">
<link href="/favicon.ico" type="image/x-icon" rel="shortcut icon">

<div class="navbar navbar-static-top">
	<div class="navbar-inner">
		<a class="brand" href="/"><?php echo htmlspecialchars($imdex->Name());?></a>
		<ul class="nav">
			<li <?php if (!$isAdmin) echo "class=\"active\"";?>><a href="."><i class="icon-folder-open"></i> Browse</a>
		<?php if ($user !== FALSE) { ?> 
			<li <?php if ($isAdmin) echo "class=\"active\"";?>><a href="?manage"><i class="icon-wrench"></i> Manage</a>
		<?php } ?>
		</ul>
	<?php if ($user === FALSE) { ?> 
	    <form class="navbar-form pull-right" action="/login.php?return=<?php echo urlencode($_SERVER['REQUEST_URI']);?>" method="post">
		    <input type="text" name="username" class="span2" placeholder="Username">
		    <input type="password" name="password" class="span2" placeholder="Password">
		    <button type="submit" class="btn">Login</button>
    	</form>
	<?php } else { ?> 
		<div class="btn-group pull-right">
			<a href="/login.php?do=logout&return=<?php echo urlencode($_SERVER['REQUEST_URI']);?>" class="btn">Logout</a>
		</div>
		<ul class="nav pull-right">
			<li><p class="navbar-text pull-right">Hi, <strong><?php echo htmlspecialchars($user["username"]);?></strong>!&nbsp;&nbsp;</p>
		</ul>
	<?php } ?>
	</div>
</div>

<div id="container" class="container-fluid">
	<div class="row-fluid">
		<div class="span2">
		<?php if ($imdex->HasFolders() || $imdex->CanGoUp()) { ?> 
			<ul class="nav nav-list well">
				<?php print_nav($imdex, $isAdmin); ?> 
			</ul>
		<?php } else if (!$imdex->HasImages()) { ?> 
			<a class="btn btn-block" href="."><i class="icon-refresh"></i> Refresh</a>
		<?php } ?> 
		</div>
		<div class="span10"> 
		<?php if (!$imdex->IsReal()) { ?> 
			<div class="alert">
				The requested directory does not exist.
			</div>
		<?php } else if ($imdex->HasImages()) { 
			print_thumbs($imdex->Images(), $isAdmin); 
		} ?> 
		</div>
	</div>
</div>

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/imdex.js"></script>