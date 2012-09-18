<?php 

$dbuser = 'username';
$dbpass = 'password';
$dbtable = 'tablename';

// Only if the request was made via POST, run the script.
if(isset($_POST) && !empty($_POST)) {
	
	// Give all variables initial values (some are only set in an if-condition, there this is necessary).
	$fgms = ""; $options1 = ""; $options2 = ""; $options3 = "";
	$bEmail= false; $email = "";
	$output = "";
	$trials = 0;
	$success = false;
	
	// Check if an e-mailaddress was given
	if(empty($_POST['email'])) {
		// Display an extra message if no e-mail was given.
		$output .= "An e-mail address was not given. The results will \nonly be displayed in the browser.\n\n";
	} else {
		// If something was typed into the e-mail field, check if it's a valid e-mailaddress.
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$bEmail = true;
			$email = $_POST['email'];
		} else {
			$output .= "The e-mailaddress entered was malformed. An e-mail \nhas not been sent.\n\n";
		}
	}
	
	// Check if any FGMs are passed to this script
	if(isset($_POST['fgm']) && !empty($_POST['fgm'])) {
		
		// Combine all FGM inputs to a single string.
		$fgms = implode('#', $_POST['fgm']);
		$fgms = str_replace(' ', '-', $fgms);	
		
		// Remove excess #-signs
		while(strpos($fgms, '##')!==false) {
			$fgms = str_replace('##', '#', $fgms);
		}
		
		// Remove the last character if this a is '#'
		if(substr($fgms, -1)=="#") {
			$fgms = substr($fgms, 0, -1);
		}
		
	} else {
		die("Error: No FGMs have been passed.");
	}
	
	// If no FGMs have been passed, stop executing and display a message.
	if(empty($fgms)) {
		die("You should enter at least one FGM.");
	}
	
	// Check if at least one option is checked in the "Part Results" option array.
	if(isset($_POST['options1']) && !empty($_POST['options1'])) {
		$options1 = implode('#',$_POST['options1']);
	} else {
		die("You should check at least one checkbox at \"Part Results\".");
	}
	
	// Check if at least one option is checked in the "Part Status" option array.
	if(isset($_POST['options2']) && !empty($_POST['options2'])) {
		$options2 = implode('#',$_POST['options2']);
	} else {
		die("You should check at least one checkbox at \"Part Status\".");
	}
	
	// Check if at least one option is checked in the "Part Quality" option array.
	if(isset($_POST['options3']) && !empty($_POST['options3'])) {
		$options3 = implode('#',$_POST['options3']);
	} else {
		die("You should check at least one checkbox at \"Best Quality\".");
	}
	
	// Check if an amount of trials has been passed
	if(isset($_POST['trials'])) {
		$trials = intval($_POST['trials']);
		//die("trials: $trials");
	} else {
		die('No trial amount specified. Please reload the page.\nIf the error persists, contact the Wageningen UR iGEM team.');
	}
	
	// Combine all arguments in one string.
	$cmdargs = "-brick ".aesc($fgms)." -part_results ".aesc($options1)." -part_status ".aesc($options2)." -best_quality ".aesc($options3);
	
	// Combine the argument string with the executing command
	$cmd = 'python igem.py '.$cmdargs.';';
	
	// Execute the program
	$cmdoutputArr = exec_extra($cmd);
	$cmdoutput = $cmdoutputArr['stdout'];
	if($cmdoutputArr['stderr']) {
		$output .= '<!-- ERROR: '.$cmdoutputArr['stderr'].'-->';
	}
	
	if($cmdoutput) {
		$output .= $cmdoutput."\n\n";
		$success = true;
	} else {
		// Failed, do we have trials left?
		if($trials>0) {
			die('0');
		} else {
			$success = false;
			$output .= "The script did not run as expected. Please contact \nthe Wageningen UR iGEM team.\n";
		}
	}
	
	//$output .= '[Debugging:] '.$cmd;
	
	// If a correct e-mail was passed, send an e-mail.
	if($bEmail) {
		$to = $email;
		$subject = "The Constructor - Output - Wageningen UR iGEM Team";
		// HTML output
		$body = '<html><body>'.$output.'</body></html>';
		// By using the e-mail headers we can set the 'from'-address and specify that our mail is a HTML e-mail.
		$headers = "From: noreply@socialwur.nl\nContent-type: text/html";
		
		// Perform the e-mail function
		if (!mail($to, $subject, $body, $headers)) {
			// If an error occurred, display a message:
			$output .= "E-mail delivery failed...";
		} else {
			// Otherwise, do nothing.
		}
	}
	
	// Store this result in the database.
	// We can use it to monitor usage of the program, as well as to reduce server load by outputting the
	// same result if someone else performs the exact same query.
	$date = date ("Y-m-d H:i:s");
	$link = mysql_connect('127.0.0.1', $dbuser, $dbpass);
	if($link) {
		if(mysql_select_db($dbtable)) {
			mysql_query("INSERT INTO queries (date, query, succes, email, result) VALUES('$date', '$cmdargs', '$success', '$email', '$output' ) ");  
		}
	}
	
	// Show the output in the webbrowser.
	echo $output;
	
} else {
	
	die("No data has been passed.");
	
}

function aesc($input) {
	$op = escapeshellarg($input);
	$op = str_replace('\'','',$op);
	return $op;
}


function exec_extra($cmd, $input='') {
	$proc=proc_open($cmd, array(0=>array('pipe', 'r'), 1=>array('pipe', 'w'), 2=>array('pipe', 'w')), $pipes); 
	fwrite($pipes[0], $input);
	fclose($pipes[0]); 
	$stdout=stream_get_contents($pipes[1]);
	fclose($pipes[1]); 
	$stderr=stream_get_contents($pipes[2]);
	fclose($pipes[2]); 
	$rtn=proc_close($proc); 
	return array('stdout'=>$stdout, 
	'stderr'=>$stderr, 
	'return'=>$rtn 
	); 
 } 
