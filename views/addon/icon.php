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
      <h2><?php echo Gdn::Translate('Upload Icon'); ?></h2>
      <ul>
         <li>
            <div class="Info"><?php echo Gdn::Translate('By uploading a file you certify that you have the right to distribute this picture and that it does not violate the Terms of Service.'); ?></div>
            <?php echo $this->Form->Label('Choose Icon (2mb max)', 'Icon'); ?>
            <?php echo $this->Form->Input('Icon', 'file', array('class' => 'File')); ?>
         </li>
      </ul>
      <?php echo $this->Form->Close('Upload'); ?>
   </div>
</div>