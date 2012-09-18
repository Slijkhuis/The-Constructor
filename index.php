<?php

$onserver = true;
$page = 'home';

if(isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])) {
	
	$req = $_SERVER['REQUEST_URI'];
	
	switch($req) {
		
		case '/about': $page = 'about'; break;
		
		case '/contact': $page = 'contact'; break;
		
		default: $page = 'home';
		
	}
	
}

?><!DOCTYPE html>
<html>

<head>
	<title>The Constructor</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
	<script src="js/ajax.js" type="text/javascript"></script>
	<link rel="stylesheet" href="css/opmaak.css.php" type="text/css" />
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-32469770-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
</head>

<body>

<div class="tc_wrapper">

	<div id="the_constructor_links">
		<?php include "content/menu.php" ?>
	</div>

	<div id="the_constructor_webapp">
		<?php include "content/$page.php" ?>
		<?php include 'content/footer.php' ?>
	</div>

	<?php include 'content/tutorial.php' ?>

</div>

<div class="bspacer"></div>

</body>
</html>