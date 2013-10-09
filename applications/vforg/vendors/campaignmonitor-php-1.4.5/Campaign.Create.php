<?php

ini_set ('display_errors', 1);
error_reporting (E_ALL);

require_once('CMBase.php');

//-----------------------------INPUT PARAMS---------------------------------------

$apikey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$clientid = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';		
$campaign_name = 'March newsletter';
$subject = 'March newsletter';
$from_name = 'John Smith';
$from_email = 'john@smith.com';
$reply_email = 'john@smith.com';
$html_content = 'http://www.campaignmonitor.com/uploads/templates/previews/template-1-left-sidebar/index.html';
$text_content = 'http://www.campaignmonitor.com/uploads/templates/previews/template-1-left-sidebar/textversion.txt';
$subscriber_listid = array('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
$subscriber_segments = "";

//-------------------------------------------------------------------------------	
	
$cm = new CampaignMonitor( $apikey );

//Optional statement to include debugging information in the result
$cm->debug_level = 1;

//This is the actual call to the method
$result = $cm->campaignCreate( $clientid, $campaign_name, $subject, $from_name, $from_email, $reply_email, $html_content, $text_content, $subscriber_listid, "" );

echo '<br><br>';
print_r($result);


//Print out the debugging info
//print_r($cm);

?>
