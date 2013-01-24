<?php
spl_autoload_register(function ($class) {
    include 'php/' . $class . '.php';
});

function print_nav($folders) {
	foreach ($folders as $value) {
		echo "<li"; 
		if (!((new Imdex($value))->HasImages()))
			echo " class=\"disabled\"";
		echo "><a href=\"{$value}/\">{$value}</a>";
	}	
}

function print_thumbs($files) {
	foreach ($files as $value) {
		echo <<<HTML
<li class="span4">
	<a href="{$value}" class="thumbnail">
		<img src="{$value}" alt="{$value}" title="{$value}">
	</a>
</li>

HTML;
	}
}

$requestDir = Path::RemoveQueryString($_SERVER["REQUEST_URI"]);
$imdex = new Imdex($requestDir);

?>

<!DOCTYPE html>
<meta charset="utf-8">
<title>Imdex</title>
<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="/css/base.css" rel="stylesheet" media="screen">

<div class="navbar navbar-static-top">
	<div class="navbar-inner">
		<a class="brand">Imdex</a>
		<ul class="nav">
			<li class="active"><a href="/">Browse</a>
		</ul>
	</div>
</div>

<div id="container" class="container-fluid">
	<div class="row-fluid">
		<div class="span2">
			<ul class="nav nav-list">
			<?php if ($imdex->CanGoUp()) { ?> 
				<li><a href="..">Go up</a>
			<?php } ?> 
			<?php print_nav($imdex->Folders()); ?> 
			</ul>
		</div>
		<div class="span10">
		<?php if ($imdex->HasImages()) { ?> 
			<ul class="thumbnails">
				<?php print_thumbs($imdex->Images()); ?> 
			</ul>
		<?php } else { ?> 
			<p class="muted">No images
		<?php } ?> 
		</div>
	</div>
</div>

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="/js/bootstrap.min.js"></script>