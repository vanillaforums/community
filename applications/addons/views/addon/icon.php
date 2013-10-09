<?php if (!defined('APPLICATION')) exit();
$this->HideSearch = TRUE;
if ($this->DeliveryType() == DELIVERY_TYPE_ALL)
	echo $this->FetchView('head');
?>
<h1><?php echo T('Upload Icon'); ?></h1>
<div class="Info"><?php echo T('By uploading a file you certify that you have the right to distribute this picture and that it does not violate the Terms of Service.'); ?></div>
<?php
echo $this->Form->Open(array('enctype' => 'multipart/form-data'));
echo $this->Form->Errors();
?>
<ul>
	<li>
		<h3><?php echo $this->Form->Label('Choose Icon (2mb max)', 'Icon'); ?></h3>
		<?php echo $this->Form->Input('Icon', 'file', array('class' => 'File')); ?>
	</li>
</ul>
<?php echo $this->Form->Close('Upload');