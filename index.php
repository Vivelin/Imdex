<?php
spl_autoload_register(function ($class) {
    include 'php/' . $class . '.php';
});

function print_nav($folders) {
	foreach ($folders as $value) {
		echo "<li><a href=\"{$value}\">{$value}</a>";
	}	
}

$requestDir = Path::RemoveQueryString($_SERVER["REQUEST_URI"]);
$imdex = new Imdex($requestDir);

?>

<!DOCTYPE html>
<meta charset="utf-8">
<title>Imdex</title>
<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">

<div class="container">
	<div class="row">
		<div class="span2">
			<ul class="nav nav-list">
				<li class="nav-header">CURRENT FOLDER
				<?php if ($imdex->CanGoUp()) { ?><li><a href="..">Go up</a><?php } ?> 
				<?php print_nav($imdex->Folders()); ?>
			</ul>
		</div>
		<div class="span10">
			<h1>Hi</h1>
			<dl>
				<dt>Requested directory
				<dd><?php echo $requestDir;?> 
				<dt>Full directory
				<dd><?php echo Path::GetFullPath($requestDir);?> 
			</dl>
		</div>
	</div>
</div>

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="/js/bootstrap.min.js"></script>