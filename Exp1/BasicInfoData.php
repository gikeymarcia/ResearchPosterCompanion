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

<!-- This asks questions at the end of each session. -->

<?php
	// Record demographic data.
	$_SESSION['Demographics'] = $_POST['Gender'] . "\t" . trim( $_POST['Age']) . "\t" . $_POST['Education'] . "\t" . $_POST['English'] . "\t" . trim( $_POST['Country']);

	// Open test file and write data.
	$fileTestData = fopen( "subjects/Test_" . $_SESSION['user'] . ".txt", "a"); 
	fputs($fileTestData, $_SESSION['user']."\t".$_SESSION['experimentName'] . "\t" . $_SESSION['sessionNum'] . "\tDemographics\t" . date('c') . "\t" . round(microtime(true) - $_SESSION['timer'],3) . "\t" . $_SESSION['Demographics'] . "\n");
?>

<meta http-equiv="refresh" content="0; url=Instructions.php"> <center><br><br>Preparing...</center> 

</body>
</html>