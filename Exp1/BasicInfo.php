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

<!-- This does demographics and consent at the start of the session. -->

<?php
	$_SESSION['timer'] = microtime(true); // start timer for this page
?>

<br><br>
<center>
<table width=500><tr><td>
	<form name="BasicInfo" action="BasicInfoData.php" autocomplete="off" method="post">
	<p><strong>Basic Information</strong></p>

		<p>What is your gender?</p>
			<table cellspacing="4" cellpadding="0" border="0">
				<tbody>
					<tr>
						<td valign="middle"><input type="radio" value="Male" name="Gender" /></td>
						<td><span class="answertext">Male</span></td>
					</tr>
					<tr>
						<td valign="middle"><input type="radio" value="Female" name="Gender" /></td>
						<td><span class="answertext">Female</span></td>
					</tr>
				</tbody>
			</table>
		
		<p>What is your age?</p>
			<p><input type="text" name="Age" size="10" id="Age" /></p>
		
		<p>Which of the following best describes your highest achieved education level?</p>
		<p><select name="Education">
			<option selected="selected">-select level-</option>
			<option>Some High School</option>
			<option>High School Graduate</option>
			<option>Some college, no degree</option>
			<option>Associates degree</option>
			<option>Bachelors degree</option>
			<option>Graduate degree (Masters, Doctorate, etc.)</option>
		</select></p>
		
		<p>Do you speak English fluently?</p>
		<table cellspacing="4" cellpadding="0" border="0">
			<tbody>
				<tr>
					<td valign="middle"><input type="radio" value="Fluent" name="English" /></td>
					<td><span class="answertext">Yes I am fluent in English</span></td>
				</tr>
				<tr>
					<td valign="middle"><input type="radio" value="Non-fluent" name="English" /></td>
					<td><span class="answertext">No, I am not fluent in English</span></td>
				</tr>
			</tbody>
		</table>
		
		<p>In what country do you live?</p>
		<p><input type="text" name="Country" size="30" id="Country" /></p>
		
		
	<center><input type="submit" value="Submit" name='submit'></center>
	</form>

</td></tr></table>
</center>

</body>
</html>