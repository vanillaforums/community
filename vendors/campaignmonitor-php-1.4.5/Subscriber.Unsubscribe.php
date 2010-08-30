<?php
	//Sample using the CMBase.php wrapper to call Subscriber.AddWithCustomFields from any version of PHP
	
	//Relative path to CMBase.php. This example assumes the file is in the same folder
	require_once('CMBase.php');
	
	//Your API Key. Go to http://www.campaignmonitor.com/api/required/ to see where to find this and other required keys
	
	$api_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
	$client_id = null;
	$campaign_id = null;
	$list_id = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
	$cm = new CampaignMonitor( $api_key, $client_id, $campaign_id, $list_id );
	
	//Optional statement to include debugging information in the result
	//$cm->debug_level = 1;
	
	//This is the actual call to the method, passing email address, name.
	$result = $cm->subscriberUnsubscribe('joe@notarealdomain.com');

	
	if($result['Result']['Code'] == 0)
		echo 'Success';
	else
		echo 'Error : ' . $result['Result']['Message'];
	
	//Print out the debugging info
	//print_r($cm);
?>