<?php if (!defined('APPLICATION')) exit();

echo wrap(t('Detach Addon from Discussion'), 'h2');

echo $this->Form->open();
echo $this->Form->errors();
?>
<ul>
     <li>
          <?php
          echo $this->Form->checkBox('DetachConfirm', 'Are you sure you want to remove the addon attachment?');
          ?>
     </li>
</ul>
<?php
echo $this->Form->close('Detach');
