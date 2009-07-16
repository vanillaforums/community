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
      <h2><?php echo Gdn::Translate('Add a Picture'); ?></h2>
      <ul>
         <li>
            <div class="Info"><?php echo Gdn::Translate('By uploading a picture you certify that you have the right to distribute this picture and that it does not violate the Terms of Service.'); ?></div>
            <?php echo $this->Form->Label('Picture to Upload (2mb max)', 'Picture'); ?>
            <?php echo $this->Form->Input('Picture', 'file', array('class' => 'File')); ?>
         </li>
      </ul>
      <?php echo $this->Form->Close('Upload'); ?>
   </div>
</div>