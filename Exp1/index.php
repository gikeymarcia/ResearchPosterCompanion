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

<body onload="document.bingo.username.focus()">

<br><br>

 
<center><table width="500"><TR><Td>

<h2>Welcome to the experiment! </h2>

<p>In this experiment, you are going to see pairs of related words. Sometimes you will see complete word pairs, other times not.
When the word pair is not complete (i.e. only the first word is shown, with a blank space for the second word), you should type your best guess of what that missing word is.  
Whether or not you were asked to guess the related word, you will then be shown the complete, correct related word. 


<p> For example, if you are shown: 
<br>Cat : __________ 

<p>You should type in what you think the "correct", related word is. For example, you might type in "Dog". 
<br>You will then see the correct answer, e.g. Cat : Kitten

<p> Or, you might be shown a complete word pair, such as Cat : Kitten. In this case, you will then see Cat : Kitten again for a second time. 

<p> Do you have any questions? 
<p>
<!--<strong>New Participants:</strong> -->
Please enter your subject number below, and click "login" when you are ready to begin.

<form name="bingo" action="login.php" method="get">
	<p><strong>Subject number</strong><br>
    <input name="username" type="text" size="25" maxlength="50"></p>
	
    <p><input type="submit" value="Login"></p>    
</form>

</td></tr></table></center>

</body>
</html>
