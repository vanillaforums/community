<?php if (!defined('APPLICATION')) exit();

echo $this->Form->Open();

echo '<p>', T('Are you sure you want to delete this?'), '</p>';


echo '<p style="text-align: center">',
    $this->Form->Button('Yes'),
    ' ',
    $this->Form->Button('No'),
    '</p>';

echo $this->Form->Close();

?>