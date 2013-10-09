<?php

ini_set ('display_errors', 1);
error_reporting (E_ALL);

require_once('CMBase.php');

//-----------------------------INPUT PARAMS---------------------------------------

$apikey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$client_id = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$accessLevel = '63';
$username = 'apiusername';
$password = 'apiPassword';
$billingType = 'ClientPaysWithMarkup';
$currency = 'USD';
$deliveryFee = '7';
$costPerRecipient = '3';
$designAndSpamTestFee = '10';

//-------------------------------------------------------------------------------	
	
	$cm = new CampaignMonitor( $apikey );
	
	//Optional statement to include debugging information in the result
	$cm->debug_level = 1;
	
	//This is the actual call to the method
	$result = $cm->clientUpdateAccessAndBilling( $client_id, $accessLevel, $username, $password, $billingType, $currency, $deliveryFee, $costPerRecipient, $designAndSpamTestFee );
	
	echo '<br><br>';
	print_r($result);
	
?>