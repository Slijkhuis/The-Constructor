<?php
	header('Content-type: text/css');
	
	$width = 500;
	
	$width2 = round($width - ($width / 3));
	$width3 =  round($width / 2);
	
	$tutwidth = 300;
	
	$total = $width + $tutwidth + 60; // 40px padding, 20px space
?>

body {
	font-family: Courier New, monospace;
	font-size: 12pt;
}

.center { text-align: center; }

label.error {
	text-indent: 20px;
	line-height: 20px;
	height: 20px;
	background: url(../images/error.png) no-repeat;
	color: red;
}

.bspacer { clear: both; height: 40px; width: 100%; }

.tc_wrapper {
	text-align: center;
	padding: 40px 40px 0 40px;
	margin: 0 auto;
	width: <?php echo $total ?>px;
}

h1 span.beta {
	color: #888;
	font-style: italic;
	font-size: 12pt;
}

a img { border: 0; }

#the_constructor_links {
	overflow: hidden;
	width: <?php echo $width ?>px;
	padding: 0 0 0 20px;
	margin: 0 auto;
}
#the_constructor_links a {
	display: block;
	padding: 8px;
	float: left;
	margin: 0 10px 0 0;
	background-color: #efefef;
	border: 2px solid #bbb;
	border-bottom: 0;
	border-radius: 3px 3px 0 0;
	color: #111;
	text-decoration: none;
}
#the_constructor_links a:hover {
	background: #ddd;
}

#the_constructor_webapp {
	text-align: left;
	margin: 0 auto;
	width: <?php echo $width ?>px;
	padding: 10px;
	border-radius: 5px;
	border: 1px solid #999;
	box-shadow: 0 0 4px #666;
	background: #ddd;
	color: #111;
	font-family: Courier New, monospace;
	font-size: 11pt;
	position: relative;
	z-index: 3;
}

#the_constructor_webapp h1 { text-align: center; }

#the_constructor_webapp .hidden { display: none; }
#the_constructor_webapp .fl { float: left; }
#the_constructor_webapp .fr { float: right; }

#the_constructor_webapp fieldset {
	padding: 0;
	margin: 0;
	border: 0;
}

#the_constructor_webapp h3 .tc_help { font-size: 11pt; }
#the_constructor_webapp h3 .tc_help:hover { font-style: italic; cursor: pointer; }

#the_constructor_webapp #help_popup {
	padding: 5px 15px;
	background-color: #eee;
	border-radius: 5px;
	display: none;
	position: absolute;
	z-indez: 10;
	max-width: 300px;
	box-shadow: 0 0 6px #999;
}

#the_constructor_webapp label {
	display: block;
	clear: both;
	width: <?php echo $width ?>px;
	margin-bottom: 2px;
}

#the_constructor_webapp .fgm label input,
#the_constructor_webapp .email label input {
	float: right;
	width: <?php echo $width2 ?>px;
}

#the_constructor_webapp .fgm { overflow: hidden; width: <?php echo $width ?>px; }
#the_constructor_webapp .fgm_controls { float: right; width: <?php echo $width2 ?>px; }

#the_constructor_webapp .tc_options { overflow: hidden; }
#the_constructor_webapp .tc_options .tc_col { width: <?php echo $width3 ?>px; overflow: hidden; }
#the_constructor_webapp .tc_options .tc_col label { width: <?php echo $width3 ?>px; }
#the_constructor_webapp .tc_options .tc_col label input { margin-right: 10px; float: left; }

#the_constructor_webapp .run { clear: both; }

#the_constructor_webapp #tc_output {
	overflow: auto;
	padding: 10px;
	width: <?php echo ($width-22) ?>px;
	height: 300px;
	background: #fefefe;
	border: 1px solid #efefef;
	border-radius: 2px;
}




#the_constructor_tutorial {
	float: right;
	display: none;
	position: relative;
	overflow: hidden;
	border-radius: 5px;
	text-align: left;
	margin: auto auto;
	width: <?php echo $tutwidth ?>px;
	padding: 10px;
	border-radius: 5px;
	border: 1px solid #999;
	box-shadow: 0 0 4px #666;
	background: #fafad2;
	color: #111;
	font-family: Courier New, monospace;
	font-size: 11pt;
	z-index: 5;
}
#the_constructor_tutorial #tutsteps {
	position: absolute;
}

#the_constructor_tutorial .tutstep {
	display: none;
}

#the_constructor_tutorial #tutstep1 { display: block }

#rfc_caution {
	font-weight: bold;
	font-style: italic;
	font-size: 10pt;
	color: rgb(232, 118, 0);
	margin: -5px 0 5px 0;
	float: left;
}
#rfc_caution:hover { font-weight: normal; margin: -6px 0 5px 0; }
#rfc_caution_hover {
	display: none;
	font-weight: normal;
	font-style: normal;
	color: #111;
	padding: 5px 20px;
	font-size: 11pt;
	width: 450px;
	position: absolute;
	border: 1px dotted yellow;
	background: #fafad2;
	border-radius: 10px;
}
#rfc_caution:hover #rfc_caution_hover { display: block; }
