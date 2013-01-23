<?php
spl_autoload_register(function ($class) {
    include 'php/' . $class . '.php';
});

$requestDir = Path::RemoveQueryString($_SERVER["REQUEST_URI"]);
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
				<li><a href="..">Go up</a>
				<li><a href="img">img</a>
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
			<pre>
<?php print_r($_SERVER);?>
			</pre>
		</div>
	</div>
</div>

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="/js/bootstrap.min.js"></script>