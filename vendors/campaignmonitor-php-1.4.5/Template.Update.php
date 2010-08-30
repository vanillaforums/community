<?php

ini_set ('display_errors', 1);
error_reporting (E_ALL);

require_once('CMBase.php');

//-----------------------------INPUT PARAMS---------------------------------------

$apikey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$template_id = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

$template_name = 'Updated Template Name';
$html_url = "http://notarealdomain.com/templates/test/index.html";
$zip_url = "http://notarealdomain.com/templates/test/images.zip";
$screenshot_url = "http://notarealdomain.com/templates/test/screenshot.jpg";

//-------------------------------------------------------------------------------	

$cm = new CampaignMonitor($apikey);

//Optional statement to include debugging information in the result
$cm->debug_level = 1;

//This is the actual call to the method
$result = $cm->templateUpdate($template_id, $template_name, $html_url, $zip_url, $screenshot_url);

echo '<br><br>';
print_r($result);

?>