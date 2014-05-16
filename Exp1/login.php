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

<?php
 
	############# Parameters ############### dfd
	$_SESSION['experimentName'] = "KHBStrength"; // This is recorded in datafile and can be useful. 
	$_SESSION['feedbackDuration'] = 5;
	$_SESSION['presentationDuration'] = 8; // For computer controlled trials
	$_SESSION['testDuration'] = 8;  // For computer controlled trials. Also applies to copy trials. 
	$_SESSION['shuffleWords'] = true; // True or false. Shuffling means words are randomly assigned to numbers in the order file. 
	$_SESSION['doDemographics'] = true; // True of false. Show a screen at the outset asking for demographic info and informed consent. 
	$numWordFiles = 1; // Set the number of word files. It selects among them randomly. 
	$numOrderFiles = 1; // Set the number of order files. It selects among them randomly. 
	############# Parameters ###############

	# this line helps avoid some problems when loading a text file that's been saved in Mac OS X
	ini_set('auto_detect_line_endings', true);
	
	// Get user name and sessionNum from the url. 
	$_SESSION['user'] = trim( $_GET['username'] );
	$_SESSION['sessionNum'] = trim( $_GET['sessionnum'] ); // this gets the sessionNum in case it's in the URL
	if ($_SESSION['sessionNum'] < 1) {$_SESSION['sessionNum'] = 1;} //if it isn't this sets it to 1. 

	

		############# They entered their info correctly, start login process ###############
		//The Status file records their browser ID etc., and when they start and stop.
		// The loginCounter counts user logins. The point is you can use it to assign people to conditions, instead of pure random assignment. 
		
		# Update status file. 
		$fileUserData = fopen( "subjects/Status.txt", "a" );
		fputs($fileUserData, $_SESSION['user'] . "\t". date('c') . "\tSession " . $_SESSION['sessionNum'] . "\tSessionStart\t" . $_SERVER['HTTP_USER_AGENT'] . "\n");
		fclose($fileUserData);
		
		# Read the LoginCounter file
		$fileLC = @fopen( "subjects/LoginCounter.txt", "r" );
		if ($fileLC) { // if the file exists
			$LoginCount = fgets($fileLC) + 1;
			fclose($fileLC);
		} else {
			$LoginCount = 1;
		}
		# Write to the LoginCounter file
		$fileLC = fopen( "subjects/LoginCounter.txt", "w" );
		fputs($fileLC, $LoginCount);
		fclose($fileLC);

		############# Import the words ###############

		// First create an array of the word pairs, and shuffle them.
		srand((double)microtime()*1000000);
		$_SESSION['wordFile'] = "Words" . rand(1,$numWordFiles) . ".txt";
		$fileWords = fopen($_SESSION['wordFile'], "r" );
		$tempArray = array(0=>0);
		$counter = 0;
		while( ( $lineOfData = fgetcsv( $fileWords, 1000, "\t" ) ) != FALSE )
		{
			if (strlen(trim($lineOfData[0])) > 0){
				$tempArray[$counter] = array(0=>$lineOfData[0], 1=>$lineOfData[1], 2=>$lineOfData[2], 3=>$lineOfData[3], 4=>0);
				$counter++;
			}
		}
		fclose($fileWords);
		
		/*if ($_SESSION['shuffleWords']) {
			shuffle($tempArray); // Shuffle the words before assigning them to stimuli file, so they're assigned to numbers in the orderfile randomly. Repeated items in the order file will still be repeated correctly. 
		}*/
		
		// new block randomization shuffle
		if ($_SESSION['shuffleWords']) {
			$arrayPos=0;																//counter to move through tempArray
			$blockPos = 0;																//counter for making to-be-randomized blocks
			$combinedArray = array();													//declares array that will hold temp output words file
			$blockArray[$arrayPos] = $tempArray[$arrayPos];								//loads first $tempArray item into block
			
			while ($arrayPos < count($tempArray)-1) {									//block randomizing functions
				if ($tempArray[$arrayPos][3] == $tempArray[$arrayPos+1][3]) {			//if next item in the array has the same group membership then load that item into the current block
					$arrayPos++;
					$blockPos++;
					$blockArray[$blockPos] = $tempArray[$arrayPos];
				}
				else {																	//if the next item in an array is from a different group then shuffle the current block
				    if($blockArray[0][3] != "Off") {
						shuffle($blockArray);												
					}
					$combinedArray = array_merge_recursive($combinedArray , $blockArray);			//add shuffled block into a holding array
					$blockPos = 0;																	//reset block and it's counter
					$blockArray = NULL;
					$arrayPos++;																	//progress to next item and load into the newly reset block
					$blockArray[$blockPos] = $tempArray[$arrayPos];
				}
			}
			if($blockArray[0][3] != "Off") {
				shuffle($blockArray);												
			}
			$combinedArray = array_merge_recursive($combinedArray , $blockArray);					//add last holding block to the holding array
			$tempArray = $combinedArray;															//set the original words array equal to the newly made block randomized holding array
			
			foreach($tempArray as $stimuli) {
				$stimuli[4]=0;
			}
			/*  making the snipet of code below active will let you verify that items are shuffled as intended
			
			echo "<pre>";
			print_r($tempArray);
			echo "</pre>";
			*/
		}
		
		############# Create stimulus files ###############
		
		if ($_SESSION['sessionNum'] == 1) {
			// Open orderfile to read from
			// This assigns orderfile number based on the number of people who have logged in. Note: You could do other between-subject variables the same way, that is using LoginCount and modulus (i.e., %). 
			$orderfilenum = (($LoginCount-1) % $numOrderFiles) + 1;
			$_SESSION['orderFile'] = "Order" . $orderfilenum . ".txt";
			$fileOrder = fopen($_SESSION['orderFile'], "r" );
			
			// Open stimuli file(s) to write to.
			$tempSessionNum = $_SESSION['sessionNum'];
			$fileStimOutput = fopen( "subjects/Stimuli_".$tempSessionNum."_" . $_SESSION['user'] . ".txt", "w" );
			while( ( $lineOfData = fgetcsv($fileOrder, 1000, "\t") ) != FALSE )
			{
				if ($lineOfData[0] == "*newfile*") {
					// Close the file you were working on and open the next one. This allows you to create multiple stimuli files during session 1. You can direct people to these stimuli files by emailing them links that specify which session they are supposed to complete. 
					fclose($fileStimOutput);
					$tempSessionNum++;
					$fileStimOutput = fopen( "subjects/Stimuli_".$tempSessionNum."_" . $_SESSION['user'] . ".txt", "w" );
				}
				else {
					fputs($fileStimOutput, $tempArray[-1+$lineOfData[0]][0] . "\t" . $tempArray[-1+$lineOfData[0]][1] . "\t" . $tempArray[-1+$lineOfData[0]][2] . "\t" . $lineOfData[1] . "\t" . $lineOfData[2] . "\t" . $lineOfData[3] . "\t" . $lineOfData[4] . "\n");
				}
			}
			fclose($fileOrder);
			fclose($fileStimOutput);
		}
		
		############# Write column names in the test file ###############

		// Write header for test.txt
		$FileTestData = fopen( "subjects/Test_" . $_SESSION['user'] . ".txt", "a" );
		// Column names
		fputs($FileTestData, "Email\tExperiment\tSessionNum\tTrial\tDate\t" . "Question\tAnswer\tResponse\tRT\tLenScore\tLenAcc\t". "StrictAcc\tStimFileInfo\tTrialType\t"."Feedback\tDurationControl\tOrderFileInfo\n");


		########### Start ############

		// Send an email alert that someone started. 
		//mail("yuyue1125@hotmail.com", $_SESSION['experimentName']." ".$_SESSION['user'], $_SESSION['user'] . "\t" . date('c'), "From: ".$_SESSION['experimentName']."@cogfog.com");
	
		// Send them to the next page
		if ($_SESSION['doDemographics']) {
			echo '<meta http-equiv="refresh" content="0; url=BasicInfo.php"> <center><br><br>Preparing...</center>'; // use this to do demographics.
		} else {
			echo '<meta http-equiv="refresh" content="0; url=Instructions.php"> <center><br><br>Preparing...</center>'; // use this to skip demographics. 
			//echo "<a href="."instructions.php".">Click Here to continue</a>";		//uncomment this line and comment the line above make the program wait for user input to progress.  Very useful for bugfixing
		}


?>
</body>
</html>



