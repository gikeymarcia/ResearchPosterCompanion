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
	$_SESSION['Q1'] = trim( $_POST['Q1']); 

	// Write final questions data (if they did final questions). 
	$fileTestData = fopen( "subjects/Test_" . $_SESSION['user'] . ".txt", "a"); 
	fputs($fileTestData, $_SESSION['user']."\t".$_SESSION['experimentName'] . "\t" . $_SESSION['sessionNum'] . "\tFQ1\t" . date('c') . "\t" . round(microtime(true) - $_SESSION['timer'],3) . "\t" . $_SESSION['Q1'] . "\t" . $_SESSION['wordFile'] . "\t" . $_SESSION['orderFile'] . "\n");
	fclose($fileTestData);
	
	$_SESSION['timer'] = microtime(true); // start timer for this page
?>

<!-- This asks questions at the end of each session. -->

<br>
<br>
<center>
<table width=500><tr><td>
	<p align=center><strong>Final Questions</strong></p>
	<p>Have you participated in this experiment (or another experiment using the same learning materials) before?</p>
	 
	<p>
	<form name="fq2" action="DoneForToday.php" autocomplete="off" method="post">
		<input type="radio" name="Q2" value="Naive ">No, this is my first time<br>
		<input type="radio" name="Q2" value="DoneItBefore">Yes, I've done it before<br>
		</p>
		<center>
		<input type="submit" value="Submit" name='submit'></center>
	</form>

</td></tr></table></center>
</body>
</html>
