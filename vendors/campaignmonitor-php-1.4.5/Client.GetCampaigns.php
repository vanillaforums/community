<?php

ini_set ('display_errors', 1);
error_reporting (E_ALL);

require_once('CMBase.php');

//-----------------------------INPUT PARAMS---------------------------------------

$apikey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$client_id = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

//-------------------------------------------------------------------------------	

$cm = new CampaignMonitor( $apikey );

//Optional statement to include debugging information in the result
$cm->debug_level = 1;

//This is the actual call to the method
$result = $cm->clientGetCampaigns( $client_id );

echo '<br><br>';
print_r($result);

?>