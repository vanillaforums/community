<?php if (!defined('APPLICATION')) exit();
$this->HideSearch = TRUE;
if ($this->DeliveryType() == DELIVERY_TYPE_ALL)
	echo $this->FetchView('head');
?>
<h1><?php echo T('Create a new Addon'); ?></h1>
<div class="Info"><?php
   echo sprintf(T('This page is only for Vanilla 2 addons.', 'This page is only for adding Vanilla 2 addons. If you want to add a Vanilla 1 addon click <a href="%s">here</a>.'), Url('/addon/addv1'));
?></div>
<?php
   echo $this->Form->Open(array('enctype' => 'multipart/form-data'));
   echo $this->Form->Errors();
?>
<ul>
   <li>
		<?php
			echo '<h3>', $this->Form->Label('Addon Archive (2mb max, must be a zip archive)', 'File'), '</h3>';
			echo $this->Form->Input('File', 'file', array('class' => 'File'));
         echo '<div class="Info">', T('By uploading a file you certify that you have the right to distribute the file and that it does not violate the Terms of Service.'), '</div>';
		?>
	</li>
   <li>
		<?php
			echo '<h3>', $this->Form->Label('Additional Description', 'Description2'), '</h3>';
			echo $this->Form->TextBox('Description2', array('multiline' => TRUE));
         echo '<div class="Info">', T('Additional Description', 'Your addon file should contain a basic description in it\'s info array. Add an additional, more detailed description here. Html allowed.'), '</div>';
		?>
	</li>
</ul>
<?php echo $this->Form->Close('Save');
