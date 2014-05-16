<?php
#Start the session at the top of every page
if (!isset ($_SESSION)) session_start();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Experiment</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<br><br><br>
<center><table width="500"><TR><Td>

<?php
	// Store the response data.
	$freeRecallResponse = trim(str_replace(chr(13).chr(10), ' ', $_POST['response'])); // replaces returns (which are a 13 and a 10, I guess) with spaces
	$freeRecallRt = round(microtime(true) - $_SESSION['timer'],3);
		
	// Prepare line of data to save.
	$tempData = $_SESSION['user']."\t".$_SESSION['experimentName']."\t".$_SESSION['sessionNum'] . "\t" . "FreeRecall" . "\t" . date('c') . "\t" . $freeRecallRt . "\t" . $freeRecallResponse . "\n";
	
	// Save data into a file based on their username
	$FileTestData = fopen( "subjects/FreeRecall.txt", "a" );
	fputs($FileTestData, $tempData);
	fclose($FileTestData);
?>

<center><table width="500"><TR><Td>
<br>
<br>
<br>

<center>

<h3>
	<a href="feedback.php">Click Here to Continue</a>
</h3>


</td></tr></table></center>


</td></tr></table></center>
</body>
</html>
