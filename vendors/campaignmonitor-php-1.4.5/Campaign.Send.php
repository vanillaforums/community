<?php

ini_set ('display_errors', 1);
error_reporting (E_ALL);

require_once('CMBase.php');

//-----------------------------INPUT PARAMS---------------------------------------

$apikey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$campaignid = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';		
$confirmationEmail = 'joe@domain.com';
$sendDate = '2008-02-15 09:00:00';

//-------------------------------------------------------------------------------	
	
	$cm = new CampaignMonitor( $apikey );
	
	//Optional statement to include debugging information in the result
	$cm->debug_level = 1;
	
	//This is the actual call to the method
	$result = $cm->campaignSend( $campaignid, $confirmationEmail, $sendDate );
	
	echo '<br><br>';
	print_r($result);
	
	
	//Print out the debugging info
	//print_r($cm);

?>
