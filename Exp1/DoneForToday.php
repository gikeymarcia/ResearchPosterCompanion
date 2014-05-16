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

<?php
	// Record response
	$_SESSION['Q2'] = trim( $_POST['Q2']); 

	// Write final questions data (if they did final questions). 
	$fileTestData = fopen( "subjects/Test_" . $_SESSION['user'] . ".txt", "a"); 
	fputs($fileTestData, $_SESSION['user']."\t".$_SESSION['experimentName'] . "\t" . $_SESSION['sessionNum'] . "\tFQ2\t" . date('c') . "\t" . round(microtime(true) - $_SESSION['timer'],3) . "\t" . $_SESSION['Q2'] . "\t" . $_SESSION['wordFile'] . "\t" . $_SESSION['orderFile'] . "\n");
	
	fputs($fileTestData, $_SESSION['user']."\t".$_SESSION['experimentName'] . "\t" . $_SESSION['sessionNum'] . "\tAllExtraInfo\t" . date('c') . "\tNA\t" . $_SESSION['Demographics'] . "\t" . $_SESSION['Q1']  . "\t" . $_SESSION['Q2'] . "\t" . $_SESSION['wordFile'] . "\t" . $_SESSION['orderFile'] . "\n");
	
	fclose($fileTestData);

	# Update status file. 
	$fileUserData = fopen( "subjects/Status.txt", "a" );
	fputs($fileUserData, $_SESSION['user'] . "\t". date('c') . "\tSession " . $_SESSION['sessionNum'] . "\tSessionEnd\t" . $_SERVER['HTTP_USER_AGENT'] . "\n");
	fclose($fileUserData);

?>

<br>
<br>
<center>
<table width="500"><TR><Td>
	<p>Congratulations, you have completed this part of the experiment! Thank you!
	<p>If you have any questions or comments, please email veronicayan@ucla.edu.	
</td></tr></table></center>

</body>
</html>