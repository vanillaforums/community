<?php if (!defined('APPLICATION')) exit();

echo wrap(t('Edit Addon Attachment'), 'h2');

echo $this->Form->open();
echo $this->Form->errors();
?>
<ul>
     <li>
          <?php
          echo $this->Form->label('Addon Name', 'Name');
          echo $this->Form->textBox('Name');
          ?>
     </li>
     <li>
          <?php
          echo $this->Form->label('Addon ID', 'AddonID');
          echo $this->Form->textBox('AddonID');
          ?>
     </li>
</ul>
<?php
echo $this->Form->close('Save');
