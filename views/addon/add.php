<?php if (!defined('APPLICATION')) exit();
$this->HideSearch = TRUE;
if ($this->DeliveryType() == DELIVERY_TYPE_ALL)
	echo $this->FetchView('head');

?>
<div class="Form">
   <div class="container_12">
      <h2><?php echo Gdn::Translate('Create a new Addon'); ?></h2>
      <?php
      echo $this->Form->Open(array('enctype' => 'multipart/form-data'));
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
               echo $this->Form->Label('Version', 'Version');
               echo $this->Form->TextBox('Version');
            ?>
         </li>
         <li>
            <div class="Info"><?php echo Gdn::Translate('Describe your addon in as much detail as possible. Html allowed.'); ?></div>
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
         <li>
            <div class="Info"><?php echo Gdn::Translate('Specify which versions you have tested your addon with: PHP, MySQL, jQuery, etc'); ?></div>
            <?php
               echo $this->Form->Label('Testing', 'TestedWith');
               echo $this->Form->TextBox('TestedWith', array('multiline' => TRUE));
            ?>
         </li>
         <li>
            <div class="Info"><?php echo Gdn::Translate('By uploading a file you certify that you have the right to distribute the file and that it does not violate the Terms of Service.'); ?></div>
            <?php
               echo $this->Form->Label('Addon Archive (2mb max)', 'File');
               echo $this->Form->Input('File', 'file', array('class' => 'File'));
            ?>
         </li>
      </ul>
      <?php echo $this->Form->Close('Save'); ?>
   </div>
</div>