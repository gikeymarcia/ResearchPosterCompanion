<?php
#Start the session at the top of every page
if (!isset ($_SESSION)) session_start();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>KHBStrength</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

<br><br>
<center>
<table width=500><tr><td>

<?php

	// This imports the stimuli and then launches the test. 
		
	// Initialize test array variable.
	$_SESSION['testArray'] = array(0=>0);
	$tempCounter = 0;
	$extraLines = "";

	// Import the stimuli. 
	$fileStimInput = fopen( "subjects/Stimuli_".$_SESSION['sessionNum']."_" . $_SESSION['user'] . ".txt", 'r' );
	while( ( $lineOfData = fgetcsv( $fileStimInput, 1000, "\t" ) ) != FALSE )
	{
		if (strtolower($lineOfData[3]) == "invisible") {
			// Look for invisible lines and don't use them (these are just so you can put lines in your datafile that don't get used, to make analysis more convenient). 
			$extraLines = $extraLines . $_SESSION['user']."\t".$_SESSION['experimentName']."\t".$_SESSION['sessionNum'] . "\tNA\tNA\t" . $lineOfData[0] . "\t" . $lineOfData[1] . "\tNA\tNA\tNA\tNA\tNA\t" . $lineOfData[2] . "\t" . $lineOfData[3] . "\t" . $lineOfData[4] . "\t" . $lineOfData[5] . "\t" . str_replace(",", "\t", $lineOfData[6]) . "\n"; # For the last column, it replaces commas with tabs. 
		} else {
			// Put info info from stimfile in the program to actually be used. 
		 	#echo implode(" <> ",$lineOfData) ."<br>"; # implode turns an array into a string
		 	$_SESSION['testArray'][$tempCounter] = array(0=>$lineOfData[0], 1=>$lineOfData[1], 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>$lineOfData[2], 8=>$lineOfData[3], 9=>$lineOfData[4], 10=>$lineOfData[5], 11=>str_replace(",", "\t", $lineOfData[6])); # For the last column, it replaces commas with tabs. 
			$tempCounter++;
		}
	}

	// Write extra lines to the datafile (if necessary). 	
	$FileTestData = fopen( "subjects/Test_" . $_SESSION['user'] . ".txt", "a" );
	fputs($FileTestData, $extraLines);
	fclose($FileTestData);
	
		
	$_SESSION['globalCounter'] = 0; # This will be used to determine which word pair to show.

	echo "<p> Sometimes you will see complete word pairs, other times not. You need to type your best guess of the missing word when the word pair is not complete. You will then be provided the correct word pairs. 

<p>Please turn off all phones and distracting electronics. This study will take around 25 minutes.</p>";
?>


<center>
<br><br><br>
<h3><a href="test.php">	Click Here to Start</a></h3>
</center>
</td></tr></table>
</center>
	
</body>
</html>