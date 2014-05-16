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
	$_SESSION['timer'] = microtime(true); // start timer for this page
?>
	<br><br>
	<center>
	<table width=500><tr><td>
		<p align=center><strong>Final Questions</strong></p>
		<p>Did the experiment go smoothly or were there problems (such as technical issues, slow internet, distracting noises)? (Note: Your compensation will not depend on your answer below, so please be honest.)</p>
		
		<p>
		<form name="fq1" action="finalQuestions2.php" autocomplete="off" method="post">
			<input type="radio" name="Q1" value="Smooth">It went smoothly. <br>
			<input type="radio" name="Q1" value="MinorBumps">There were minor problems.<br>
			<input type="radio" name="Q1" value="ExcludeMe">There were significant problems. I don't think my responses should be included in the data.<br>
			</p>
			<center>
			<input type="submit" value="Submit" name='submit'></center>
		</form>
	
	
	</td></tr></table>
	</center>

</body>
</html>
