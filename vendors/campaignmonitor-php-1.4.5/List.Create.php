<?php

ini_set ('display_errors', 1);
error_reporting (E_ALL);

require_once('CMBase.php');

//-----------------------------INPUT PARAMS---------------------------------------

$apikey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$clientid = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';		
$title = 'API Newsletter Subscribers';
$unsubscribePage = '';
$confirmOptIn = 'false';
$confirmationSuccessPage = '';

//-------------------------------------------------------------------------------	
	
	$cm = new CampaignMonitor( $apikey );
	
	//Optional statement to include debugging information in the result
	$cm->debug_level = 1;
	
	//This is the actual call to the method
	$result = $cm->listCreate( $clientid, $title, $unsubscribePage, $confirmOptIn, $confirmationSuccessPage );
	
	echo '<br><br>';
	print_r($result);
	
?>