<?php if (!defined('APPLICATION')) exit();

echo $this->Form->open();

echo '<h2>Delete Addon</h2>';
echo '<p>', t('Delete this addon completely?'), '</p>';

echo '<p style="text-align: center">',
    $this->Form->button('Yes'),
    ' ',
    $this->Form->button('No'),
    '</p>';

echo $this->Form->close();