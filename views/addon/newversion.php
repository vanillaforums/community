<?php if (!defined('APPLICATION')) exit();
$this->HideSearch = TRUE;
if ($this->DeliveryType() == DELIVERY_TYPE_ALL)
	echo $this->FetchView('head');
   
?>
<div class="Form">
   <div class="container_12">
      <?php
      echo $this->Form->Open(array('enctype' => 'multipart/form-data'));
      echo $this->Form->Errors();
      ?>
      <h2><?php echo Gdn::Translate('Upload a New Version'); ?></h2>
      <ul>
         <li>
            <div class="Info"><?php echo Gdn::Translate('By uploading a file you certify that you have the right to distribute this picture and that it does not violate the Terms of Service.'); ?></div>
            <?php echo $this->Form->Label('File to Upload (2mb max)', 'File'); ?>
            <?php echo $this->Form->Input('File', 'file', array('class' => 'File')); ?>
         </li>
         <li>
            <?php
               echo $this->Form->Label('New Version Number', 'Version');
               echo $this->Form->TextBox('Version');
            ?>
         </li>
         <li>
            <div class="Info"><?php echo Gdn::Translate('Specify which versions you have tested the new version of your addon with: PHP, MySQL, jQuery, etc'); ?></div>
            <?php
               echo $this->Form->Label('Testing Information', 'TestedWith');
               echo $this->Form->TextBox('TestedWith', array('multiline' => TRUE));
            ?>
         </li>
      </ul>
      <?php echo $this->Form->Close('Upload'); ?>
   </div>
</div>