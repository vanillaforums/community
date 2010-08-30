<?php

ini_set ('display_errors', 1);
error_reporting (E_ALL);

require_once('CMBase.php');

//-----------------------------INPUT PARAMS---------------------------------------

$apikey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$list_id = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';	

// Text field example
$fieldName = 'Nickname';
$dataType = 'Text';
$options = '';

/*
// Below are examples for the other possible field types

// Number field example
$fieldName = 'Age';
$dataType = 'Number';
$options = '';

// Multi-option select one example
$fieldName = 'Sex';
$dataType = 'MultiSelectOne';
$options = 'Male||Female';

// Multi-option select many example
$fieldName = 'Hobby';
$dataType = 'MultiSelectMany';
$options = 'Surfing||Reading||Snowboarding';

*/

//-------------------------------------------------------------------------------	
	
	$cm = new CampaignMonitor( $apikey );
	
	//Optional statement to include debugging information in the result
	$cm->debug_level = 1;
	
	//This is the actual call to the method
	$result = $cm->listCreateCustomField( $list_id, $fieldName, $dataType, $options );
	
	echo '<br><br>';
	print_r($result);
	
?>