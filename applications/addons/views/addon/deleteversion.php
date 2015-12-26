<?php if (!defined('APPLICATION')) exit();

echo $this->Form->open();

echo '<p>', t('Are you sure you want to delete this?'), '</p>';


echo '<p style="text-align: center">',
    $this->Form->button('Yes'),
    ' ',
    $this->Form->button('No'),
    '</p>';

echo $this->Form->close();