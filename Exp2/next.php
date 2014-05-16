<?php
	session_start();
	require("CustomFunctions.php");							// Loads all of my custom PHP functions
	
	#### setting up aliases (for later use)
	$currentPos		=& $_SESSION['Position'];
	$currentTrial	=& $_SESSION['Trials'][$currentPos];
		$cue		=& $currentTrial['Stimuli']['Cue'];
		$target		=& $currentTrial['Stimuli']['Target'];
		$answer		=  $currentTrial['Stimuli']['Answer'];
		$trialType	=  trim(strtolower($currentTrial['Info']['Trial Type']));
	
	
	#### grabbing responses from postTrial
	@$currentTrial['Response']['JOL']		= $_POST['JOL'];
	@$currentTrial['Response']['postRT']	= $_POST['RT'];
	@$currentTrial['Response']['postRTkey']	= $_POST['RTkey'];
	## ADD ## if you've made a new post-trial type that collects data then you need to record that data into $currentTrial['Response']['whatever name']
	
	
	#### Writing to data file
	$fileName = 'subjects/Output_Session'.$_SESSION['Session'].'_'.$_SESSION['Username'].'.txt';
	$add = array(		$_SESSION['Username'],
						$_SESSION['ExperimentName'],
						$_SESSION['Session'],
						$_SESSION['Position'],
						date("c"),
						$_SESSION['Condition']['Number'],
						$_SESSION['Condition']['Stimuli'],
						$_SESSION['Condition']['Order'],
						$_SESSION['Condition']['Condition Description'],
						$_SESSION['Condition']['Condition Notes'],
					);
	$addHeader = array(	'Username',
						'ExperimentName',
						'Session',
						'Trial',
						'Date',
						'Condition Number',
						'Stimuli File',
						'Order File',
						'Condition Description',
						'Condition Notes',
					);
	// If the output file doesnt exist then write headers
 	if (is_file($fileName) == FALSE) {
		$Header1	= $_SESSION['Header1'];
		$Header2	= $_SESSION['Header2'];
		for($i=count($addHeader)-1; $i >=0; $i--) {
			array_unshift($Header1,"");									// add blanks to beginning of $Header1
			array_unshift($Header2,$addHeader[$i]);						// add column names to beginning of $Header2
		}
		// combine header info into 1 line
		$combinedHeader = array();
		for($i=0; $i<count($Header1); $i++) {
			$combinedHeader[] = $Header1[$i].'*'.$Header2[$i];
		}
		arrayToLine($combinedHeader,$fileName);
	}
	// write line of data for this trial
	$Header1	=& $_SESSION['Header1'];
	$Header2	=& $_SESSION['Header2'];
	$data		=  array();
	foreach ($add as $value) {
		$data[] = $value;
	}
	// removing chars that screw up output file
	$junk = array( '\n' , '\t' , '\r' , chr(10) , chr(13) );
	for($pos=0; $pos<count($Header1); $pos++) {
		$dataBit = str_replace($junk,' <br /> ', $currentTrial[$Header1[$pos]][$Header2[$pos]]);
		$data[] = $dataBit;
	}
	arrayToLine($data,$fileName);										// write data line to the file
	###########################################
	
	
    // progresses the trial counter
	$currentPos++;
	
	header("Location: test.php");
	exit;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="css/global.css" rel="stylesheet" type="text/css" />
	<link href='http://fonts.googleapis.com/css?family=Kreon' rel='stylesheet' type='text/css' />
	<title>Test/Presentation</title>
</head>

<body>
	<!-- send back to test -->
	<meta http-equiv="refresh" content="0; url=test.php">
</body>
