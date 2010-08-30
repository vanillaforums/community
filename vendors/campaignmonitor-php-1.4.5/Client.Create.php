<?php

ini_set ('display_errors', 1);
error_reporting (E_ALL);

require_once('CMBase.php');

//-----------------------------INPUT PARAMS---------------------------------------

$apikey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$companyName = 'Created From API';		
$contactName = 'Joe Smith';
$emailAddress = 'joe@domain.com';
$country = 'United States of America';
$timezone = '(GMT-05:00) Eastern Time (US & Canada)';

//-------------------------------------------------------------------------------	
	
	$cm = new CampaignMonitor( $apikey );
	
	//Optional statement to include debugging information in the result
	$cm->debug_level = 1;
	
	//This is the actual call to the method
	$result = $cm->clientCreate( $companyName, $contactName, $emailAddress, $country, $timezone );
	
	echo '<br><br>';
	print_r($result);
	
?>