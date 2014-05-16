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
	$_SESSION['testArray'][$_SESSION['globalCounter']-1][2] = trim($_POST['response']);
	$timeForWord = round(microtime(true) - $_SESSION['timer'],3);
	$_SESSION['testArray'][$_SESSION['globalCounter']-1][3] = $timeForWord;
	
	//If one of the multibuttons was pressed, record which one was pressed by writing over the ['response'] variable. 
	//Note 1: You can easily have more buttons. Just add appropriate elseif statments here and add aditional buttons in test.php. 
	//Note 2: Right now this is set up so they can't type something in when using multibutton; this would have to change if you want them to type and then choose a button. In that case, you'd probably want to add another column of data, I think, for which button was pressed. 
	if ($_POST['b1']) {
		$_SESSION['testArray'][$_SESSION['globalCounter']-1][2] = "b1";
	} elseif ($_POST['b2']) {
		$_SESSION['testArray'][$_SESSION['globalCounter']-1][2] = "b2";
	}
	
	// Figure out lenient accuracy.
	similar_text(strtolower($_SESSION['testArray'][$_SESSION['globalCounter']-1][2]), strtolower($_SESSION['testArray'][$_SESSION['globalCounter']-1][1]), $lenientScore);
	$_SESSION['testArray'][$_SESSION['globalCounter']-1][4] = $lenientScore;
	if ($lenientScore >= 75) {
		$_SESSION['testArray'][$_SESSION['globalCounter']-1][5] = 1;
	} else {
		$_SESSION['testArray'][$_SESSION['globalCounter']-1][5] = 0;
	}
	
	// Figure out strict accuracy.
	$strictAcc = 0;
	if ( strtolower($_SESSION['testArray'][$_SESSION['globalCounter']-1][2]) == strtolower($_SESSION['testArray'][$_SESSION['globalCounter']-1][1]) ) {
		$strictAcc = 1;
	}
	$_SESSION['testArray'][$_SESSION['globalCounter']-1][6] = $strictAcc;

	// Prepare line of data to save.
	$i = $_SESSION['globalCounter'] - 1;
	$tempData = $_SESSION['user']."\t".$_SESSION['experimentName']."\t".$_SESSION['sessionNum'] . "\t" . strval($i+1) . "\t" . date('c') . "\t";
	for ($j = 0; $j < 12; $j++) {
		$tempData = $tempData . $_SESSION['testArray'][$i][$j] . "\t";}
	$tempData = $tempData . "\n";
	
	// Save data into a file based on their username
	$FileTestData = fopen( "subjects/Test_" . $_SESSION['user'] . ".txt", "a" );
	fputs($FileTestData, $tempData);
	fclose($FileTestData);

// Name the cue word
$word = $_SESSION['testArray'][$_SESSION['globalCounter']-1][0]; //display the first of every word pair as globalCounter increases
	
	// Give feedback. 
	if ( $_SESSION['testArray'][$_SESSION['globalCounter']-1][9] == "Feedback" ) {
		echo "<br>". $word . " : " . $_SESSION['testArray'][$_SESSION['globalCounter']-1][1] ."\n";
		echo '<meta http-equiv="refresh" content="' . $_SESSION['feedbackDuration'] . '; url=test.php">'; # show feedback for the appropriate duration.
	} else {
		echo '<meta http-equiv="refresh" content=".1; url=test.php">';
	}
?>

</td></tr></table></center>
</body>
</html>
