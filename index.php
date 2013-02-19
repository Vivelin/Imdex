<?php
spl_autoload_register(function ($class) {
    include 'php/' . $class . '.class.php';
});

function print_nav($imdex) {
	if ($imdex->CanGoUp()) {
		$parent = htmlspecialchars($imdex->Parent()->Name());
		echo "<li><a href=\"..\"><i class=\"icon-chevron-left\"></i> {$parent}</a>";
	}

	foreach ($imdex->Folders() as $value) {
		$sub = new Imdex($imdex->Path() . DIRECTORY_SEPARATOR . $value);
		$name = htmlspecialchars($value);
		$url = urldecode($value);

		echo "<li"; 
		if ($sub->IsEmpty())
			echo " class=\"disabled\"";
		echo "><a href=\"{$url}/\">{$name}</a>";
	}
}

function print_thumbs($files) {
	$chunks = array_chunk($files, 3);
	foreach ($chunks as $row)
	{
		echo "\t\t\t<ul class=\"thumbnails\">\n";
		foreach ($row as $value) {
			$name = htmlspecialchars($value);
			$url = urlencode($value);
			echo <<<HTML
				<li class="span4"><a href="{$url}" class="thumbnail"><img src="{$url}" alt="{$name}" title="{$name}"></a>

HTML;
		}
		echo "\t\t\t</ul>\n";
	}	
}

$requestDir = Path::RemoveQueryString($_SERVER["REQUEST_URI"]);
$imdex = new Imdex($requestDir);

?>

<!DOCTYPE html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Images in <?php echo $imdex->Name();?></title>
<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="/css/base.css" rel="stylesheet" media="screen">
<link href="/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">

<div class="navbar navbar-static-top">
	<div class="navbar-inner">
		<ul class="nav">
			<li class="active"><a href=".">Browse</a>
		</ul>
	</div>
</div>

<div id="container" class="container-fluid">
	<div class="row-fluid">
		<div class="span2">
		<?php if ($imdex->HasFolders() || $imdex->CanGoUp()) { ?> 
			<ul class="nav nav-list well">
				<li class="nav-header"><?php echo $imdex->Name();?> 
				<?php print_nav($imdex); ?> 
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
			print_thumbs($imdex->Images()); 
		} ?> 
		</div>
	</div>
</div>

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="/js/bootstrap.min.js"></script>