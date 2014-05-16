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

<body onload="document.testbox.response.focus()">
<br><br><br><center><table width="500"><TR><Td>

<?php

// This runs the presentation and test trials. 

// If we are not yet at the end of the array, display the next word
if ($_SESSION['globalCounter']< sizeof($_SESSION['testArray']))
{ 	
	
	$_SESSION['timer'] = microtime(true);	
	$_SESSION['globalCounter']++;
	
	$trialType = strtolower($_SESSION['testArray'][$_SESSION['globalCounter']-1][8]);
	$cue = $_SESSION['testArray'][$_SESSION['globalCounter']-1][0]; 
	$target = $_SESSION['testArray'][$_SESSION['globalCounter']-1][1];
	
	################ present pair ################
	if ($trialType == "read") {
		//Note: It sends them to feedback.php because that way the data are recorded. 

		// Show the pair. 
		echo $cue;
		echo " : " . $target;

		if (substr($_SESSION['testArray'][$_SESSION['globalCounter']-1][10],0,4) == "User") {	
			// This makes the timing of presentation trials user-controlled. 
			echo '<form name="testbox" action="feedback.php" autocomplete="off" method="post"><p><input type="submit" value="Done"></p></form>';
		} else {
			// This makes the timing of presentation trials automatic (i.e., computer-controlled). 
			echo '<meta http-equiv="refresh" content="' . $_SESSION['presentationDuration'] . '; url=feedback.php">'; 
		}		
	} 
	################ test pair (includes copy condition) ################
	elseif ($trialType == "test" or $trialType == "copy" or $trialType == "novowels" or $trialType == "jol") {
		// Begin form
		echo '<form name="testbox" action="feedback.php" autocomplete="off" method="post">';
		// Show the cue
		echo $cue;
		// Show target if it's a copy trial
		if ($trialType == "copy") {
			echo " : " . $target . "</p>";
			echo "<p>".$cue." : ";
		}
		// Show target with vowels removed if it's a NoVowels trial. 
		elseif ($trialType == "novowels") {
			$vowels = array("a", "e", "i", "o", "u", "y", "A", "E", "I", "O", "U", "Y");
			$onlyconsonants = str_replace($vowels, "_", $target);
			echo " : " . $onlyconsonants . "</p>";
			echo "<p>".$cue." : ";
		} elseif ($trialType == "jol") {
			echo " : " . $target;
			echo "<br>Chance you'll recall this item (0-100) ";
		}
		// Don't show target if it's a test trial. 
		else {
			echo " : ";
		}
		
		// Show test box.
    	echo '<input name="response" type="text" size="25" maxlength="50"></p>';
    	
		##### Test duration is user-controlled. #####
		if (substr($_SESSION['testArray'][$_SESSION['globalCounter']-1][10],0,4) == "User") {
			// Show submit button and end form.
    		echo '<p><input type="submit" value="Submit"></p>';
			echo '</p></form>';
    	} 
		##### Test duration is computer-controlled. #####
    	else {
    		// End form.
    		#echo '<p><br><br><br><br>Seconds allowed: ' . $_SESSION['testDuration'];
			echo '</p></form>';
			// This script disables the enter key (otherwise they can advance through timed trials).  
			echo '
			<script type="text/javascript"> 
				function stopRKey(evt) { 
					var evt = (evt) ? evt : ((event) ? event : null); 
					var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
					if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
				} 
				document.onkeypress = stopRKey; 
			</script>';
			// This script triggers the form above (here called testbox) after the duration provided in the 2nd parameter.
			echo '
			<script type="text/javascript">
				var t=setTimeout("document.testbox.submit()",' . 1000*$_SESSION['testDuration'] . ');
			</script>';
		}

	} 
	################ step out ################
	elseif ($trialType == "stepout") {
		// This is a way to leave the test cycle for a while and go to whatever page is specified in the extra information column of the orderfile. Presumably that page will take the subject to feedback whenever it's done doing whatever it does. 
		echo '<meta http-equiv="refresh" content="0; url='.$_SESSION['testArray'][$_SESSION['globalCounter']-1][11].'"> <center><br><br>Finishing...</center>';	
	}

} else { //if we are at the end of the array
	
	echo '<meta http-equiv="refresh" content="0; url=finalQuestions.php"> <center><br><br>Finishing...</center>';	
		
} //end else
?>
</td></tr></table></center>
</body>
</html>
