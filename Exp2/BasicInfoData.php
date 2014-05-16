<?php
	ini_set('auto_detect_line_endings', true);				// fixes problems reading files saved on mac
	session_start();										// start the session at the top of each page
	if ($_SESSION['Debug'] == FALSE) {
		error_reporting(0);
	}
	require("CustomFunctions.php");							// Loads all of my custom PHP functions
	
	$DemographicsFile = 'subjects/demographics.txt';
	
	$gender = $_POST['Gender'];
	$age = $_POST['Age'];
	$education = $_POST['Education'];
	$english = $_POST['English'];
	$country = $_POST['Country'];
	
	$header = array(	'Username',
						'Gender',
						'Age',
						'Education Level',
						'English Fluency',
						'Country of Origin'
						);
						
	$data = array(		$_SESSION['Username'],
						$gender,
						$age,
						$education,
						$english,
						$country
						);
	// if no demographics file exists then write the file header
	if(is_file($DemographicsFile) == FALSE) {
		arrayToLine($header,$DemographicsFile);
	}
	// write user demographics data to demographics file
	arrayToLine($data,$DemographicsFile);
	
	header("Location: instructions.php");
	exit;
?>