<?php
	ini_set('auto_detect_line_endings', true);				// fixes problems reading files saved on mac
	@session_destroy();
	session_start();										// Start the session at the top of every page
	@$_SESSION['Debug'] = $_GET['Debug'];
	if ($_SESSION['Debug'] == FALSE) {
		error_reporting(0);
	}
	require("CustomFunctions.php");							// Loads all of my custom PHP functions
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="css/global.css" rel="stylesheet" type="text/css" />
	<link href='http://fonts.googleapis.com/css?family=Kreon' rel='stylesheet' type='text/css' />
	<title>Preparing the Experiment</title>
</head>


<body>
	
	<?php
		echo '<h1>Please wait while we load the experiment...</h1>';
		echo '<noscript>	<h1>	You must enable javascript to participate!!!	</h1>	</noscript>';
		
		##### Parameters #####			## SET ##
		$_SESSION['ExperimentName']	= "FRET Confusion v1.2";								// Recorded in datafile and can be useful. 
		$_SESSION['LoginCounter Location'] = "LoginCounter/1.txt";				// Change to restart condition cycling
		// these timings only apply when trials are set as "Computer" timing
		$_SESSION['StudyTime']		= 13;										// in seconds/trial (Study/StudyPic/Instruct)
		$_SESSION['TestTime'] 		= 8;										// in seconds/trial (Test/TestPic/Copy/MCpic)
		$_SESSION['PassageTime']	= 10;										// in seconds/trial (Passage)
		$_SESSION['FreeRecallTime'] = 2;										// in seconds/trial (FreeRecall)
		$_SESSION['jolTime'] 		= user;										// in seconds/trial	(JOL) - can also use value 'user'
		$_SESSION['FeedbackTime']	= 5;										// in seconds/trial - can also use value 'user'
		$_SESSION['Demographics']	= TRUE;									// Can be TRUE or FALSE
		##### Parameters END #####
		
		
		#### Grabbing submitted info
		$_SESSION['Username']	= trim($_GET['Username']);			// grab Username from URL
		$_SESSION['Session']	= trim($_GET['Session']);			// grab session# from URL
		if( $_SESSION['Session'] < 1 ){								// if session is not set then set to 1
			$_SESSION['Session'] = 1;
		}
		else { $_SESSION['Demographics'] = FALSE; }					// skip demographics for all but session1
		$selectedCondition = trim($_GET['Condition']);
		
		
		#### Code to automatically choose condition assignment
		$Conditions	=  GetFromFile("Conditions.txt");				// Loading conditions info
		$logFile	=& $_SESSION["LoginCounter Location"];
		if( $selectedCondition == 'Auto') {
			
			if(file_exists($logFile) ) {							// Read counter file & save value
				$fileHandle	= fopen ($logFile, "r");
				$loginCount	= fgets($fileHandle);
				fclose($fileHandle);
			} else { $loginCount = 1; }
			// write old value + 1 to login counter
			$fileHandle	= fopen($logFile, "w");
			fputs($fileHandle, $loginCount + 1);
			fclose($fileHandle);
			
			$conditionNumber = ( ($loginCount-1) % (count($Conditions)-2) ) +1;		// cycles through current condition assignment based on login counter
		}
		else{
			$conditionNumber = $selectedCondition;									// if condition is manually choosen then honor choice
		}
		
		
		#### loads condition info into $_Session['Condition']
		foreach ($Conditions as $Acond) {
			if($Acond['Number'] == $conditionNumber) {
				$_SESSION['Condition'] = $Acond;
				break;
			}
		}
		// echo 'Username = '.$_SESSION['Username'].'</br>';											#### DEBUG ####
		// Readable($Conditions, "conditions loaded in");												#### DEBUG ####
		// echo "{$loginCount} logins and should be using condition {$conditionNumber}<br />";			#### DEBUG ####
		// Readable($_SESSION["Condition"],"this is what you're getting for condition:");				#### DEBUG ####
		
		
		#### Record info about the person starting the experiment to StatusFile.txt
		// information about the user loging in
		$UserData = array(
							$_SESSION['Username'] ,
							date('c') ,
							"Session " . $_SESSION['Session'] ,
							"Session Start" ,
							"Condition# {$_SESSION['Condition']['Number']}",
							$_SESSION['Condition']['Stimuli'],
							$_SESSION['Condition']['Order'],
							$_SESSION['Condition']['Condition Description'],
							$_SERVER['HTTP_USER_AGENT']
						 );
		// header row for the Status File
		$UserDataHeader = array(
							"Username" ,
							"Date" ,
							"Session #" ,
							"Begin/End?" ,
							"Condition #",
							"Words File",
							"Order File",
							"Condition Description",
							"User Agent Info"
						 );
		// if the file doesn't exist, write the header
	 	if (is_file("subjects/Status.txt") == FALSE) {
	 		arrayToLine ($UserDataHeader, "subjects/Status.txt");
	 	}
		arrayToLine ($UserData, "subjects/Status.txt");						// write $UserData to "subjects/Status.txt"
		###########################################################################
		
		
		#### Load all Stimuli and Info for this participant's condition then combine to create the experiment
		if($_SESSION['Session'] == 1) {
			// load and block shuffle stimuli for this condition
			$stimuli = GetFromFile($_SESSION['Condition']['Stimuli']);
			$stimuli = BlockShuffle($stimuli, "Shuffle");
			
			// load and block shuffle order for this condition
			$order = GetFromFile($_SESSION['Condition']['Order']);
			$order = BlockShuffle($order, "Shuffle");
						
			// Load entire experiment into $Trials[1-X] where X is the number of trials
			$Trials = array(0=> 0);
			for ($count=2; $count<count($order); $count++) {
				$Trials[$count-1]['Stimuli']	= $stimuli[ ($order[$count]['Item']) ];			// adding 'Stimuli', as an array, to each position of $Trials
				$Trials[$count-1]['Info']		= $order[$count];								// adding 'Info', as an array, to each position of $Trials
				$Trials[$count-1]['Response']	= array(	"Response1"		=> NULL,			// adding 'Response', as an array, to each position of $Trials
															"RT"			=> NULL,
															"RTkey"			=> NULL,
															"RTlast"		=> NULL,
															"strictAcc"		=> NULL,
															"lenientAcc"	=> NULL,
															"Accuracy"		=> NULL,
															"PastResponse"	=> NULL,
															"PastMatch"		=> NULL,
															"JOL"			=> NULL,
															"postRT"		=> NULL,
															"postRTkey"		=> NULL,
															## ADD ## if you're going to collect any responses you need to create the response placeholder here
														);
															
				// on trials with no Stimuli info (e.g., freerecall) keep the same Stimuli structure but fill with 'n/a' values
				// I need a consistent Trial structure to do all of the automatic output creation I do later on
				if($Trials[$count-1]['Stimuli'] == NULL) {
					$stim		=& $Trials[$count-1]['Stimuli'];
					$stim		=  $stimuli[2];
					$stimKey	= array_keys($stim);
					$empty		= array_fill_keys($stimKey, 'n/a');
					$Trials[$count-1]['Stimuli'] = $empty;
				}
			}
			
			// determine stimuli headers
			$example = $Trials[1];
			$header1 = array();
			$header2 = array();
			foreach($example as $key => $array) {
				foreach ($array as $subKey => $value) {
					$header1[] = $key;
					$header2[] = $subKey;
				}
			}
			// will use these later to record data
			$_SESSION['Header1'] = $header1;
			$_SESSION['Header2'] = $header2;
			
			######## Go through $Trials and write session file(s)
			// session files go into subjects folder and will be formatted as Username_Session1_StimuliFile.txt
			$fileNumber		= 1;
			$foreachcount	= 1;
			foreach ($Trials as $Trial) {
				if($foreachcount == 1) {
					$foreachcount++;
					continue;
				}
				#### TO DO #### Write *newfile* to the session files when it comes up so the page redirect will be able to ask questions only after the final session (where there are no *newfile* lines loaded)
				// write to next file when we hit a newfile line
				$item = strtolower(trim($Trial['Info']['Item']));
				if($item == '*newfile*') {
					$fileNumber++;
					continue;
				}
				
				// if file doesn't exist then write the 2 header lines
				$sessionFile = 'subjects/'.$_SESSION['Username'].'_Session'.$fileNumber.'_StimuliFile.txt';
				if(is_file($sessionFile) == FALSE) {
					arrayToLine ($header1, $sessionFile);
					arrayToLine ($header2, $sessionFile);
				}
				
				// write ['Stimuli'] ['Info'] and ['Response'] data to next line of the file
				$line = NULL;
				for($i= 0; $i <= count($header1); $i++) {
					$line[] = $Trial[$header1[$i]] [$header2[$i]];
				}
				arrayToLine($line,$sessionFile);
			
			}
		}
		#### Loading up $Trials for multisession experiments
		else {
			// Load headers from correct stimuli files	
			$fileNumber				= $_SESSION['Session'];
			$sessionFile			= 'subjects/'.$_SESSION['Username'].'_Session'.$fileNumber.'_StimuliFile.txt';
			$openSession			= fopen($sessionFile, 'r');
			$header1				= fgetcsv($openSession,0,"\t");
			$header2				= fgetcsv($openSession,0,"\t");
			$_SESSION['Header1']	= $header1;
			$_SESSION['Header2']	= $header2;
			
			// Loading up $Trials for this Username and Session
			$Trials		= array();
			$Trials[0]	= NULL;
			$tPos		= 0;
			while($line = fgetcsv($openSession,0,"\t")) {
				$tPos++;
				for($i=0; $i < count($line)-1; $i++) {
					$Trials[$tPos][$header1[$i]][$header2[$i]] = $line[$i];
				}
			}
		}
		// readable($header1,'header top');																#### DEBUG ####
		// readable($header2,'header 2nd');																#### DEBUG ####
		
		
		#### Establishing $_SESSION['Trials'] as the place where all experiment trials are stored
		// session1 $Trials also contains trials for other sessions but test.php sends to done.php once a *newfile* shows up
		$_SESSION['Trials']		= $Trials;
		$_SESSION['Position']	= 1;
		// Readable($_SESSION['Trials'], '$_SESSION[\'Trials\']');										#### DEBUG ####
		
		
		### PRE-CACHES All cues, targets, and answers used in experiment ####
		echo '<div class="PreCache">';
			foreach ($Trials as $Trial) {
				echo show($Trial['Stimuli']['Cue']).' <br />';
				echo show($Trial['Stimuli']['Target']).' <br />';
				echo show($Trial['Stimuli']['Answer']).' <br />';
			}
		echo '</div>';
		
		
		#### Send participant to next phase of experiment (demographics or test.php)
		if($_SESSION['Demographics'] == TRUE) {
			$link = 'BasicInfo.php';
		}
		else {
			$link = 'instructions.php';
		}
		echo '<form id="loadingForm" action="'.$link.'" method="get"> </form>';
		
	?>
	<script src="javascript/jquery-1.7.2.min.js" type="text/javascript"> </script>
	<script src="javascript/jsCode.js" type="text/javascript"> </script>
</body>
</html>