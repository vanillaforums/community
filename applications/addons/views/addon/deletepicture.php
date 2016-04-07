<?php if (!defined('APPLICATION')) exit();

echo $this->Form->open();

echo wrap(t('Delete Screenshot'), 'h2');
echo wrap(t('Delete this screenshot of the addon?'),'p');

echo wrap($this->Form->button('Yes') . ' ' . $this->Form->button('No'), 'p', ['class' => 'Center']);

echo $this->Form->close();