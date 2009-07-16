<?php if (!defined('APPLICATION')) exit();
$this->HideSearch = TRUE;
if ($this->DeliveryType() == DELIVERY_TYPE_ALL)
	echo $this->FetchView('head');
   
?>
<div class="Form">
   <div class="container_12">
      <h2><?php echo Gdn::Translate('Edit Addon'); ?></h2>
      <?php
      echo $this->Form->Open();
      echo $this->Form->Errors();
      ?>
      <ul>
         <li>
            <?php
               echo $this->Form->CheckBox('Vanilla2', 'This Addon is for Vanilla 2', array('value' => '1'));
            ?>
         </li>
         <li>
            <?php
               echo $this->Form->Label('Type of Addon', 'AddonTypeID');
               echo $this->Form->DropDown(
                  'AddonTypeID',
                  $this->TypeData,
                  array(
                     'ValueField' => 'AddonTypeID',
                     'TextField' => 'Label',
                     'IncludeNull' => TRUE
                  ));
            ?>
         </li>
         <li>
            <?php
               echo $this->Form->Label('Name', 'Name');
               echo $this->Form->TextBox('Name');
            ?>
         </li>
         <li>
            <?php
               echo $this->Form->Label('Description', 'Description');
               echo $this->Form->TextBox('Description', array('multiline' => TRUE));
            ?>
         </li>
         <li>
            <div class="Info"><?php echo Gdn::Translate('Specify any requirements your addon has, including: php version, mysql version, jquery version, browser & version, etc'); ?></div>
            <?php
               echo $this->Form->Label('Requirements', 'Requirements');
               echo $this->Form->TextBox('Requirements', array('multiline' => TRUE));
            ?>
         </li>
      </ul>
      <?php echo $this->Form->Close('Save'); ?>
   </div>
</div>