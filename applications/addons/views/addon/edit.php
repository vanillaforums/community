<?php if (!defined('APPLICATION')) exit();
$this->HideSearch = TRUE;
if ($this->DeliveryType() == DELIVERY_TYPE_ALL)
	echo $this->FetchView('head');

?>
<h1><?php echo T('Edit Addon'); ?></h1>
<?php
echo $this->Form->Open();
echo $this->Form->Errors();
?>
<ul>
	<li>
		<?php
			echo '<h3>', $this->Form->Label('Additional Description', 'Description2'), '</h3>';
         echo '<div class="Info">', T('Additional Description', 'Your addon file should contain a basic description in it\'s info array. Add an additional, more detailed description here. Html allowed.'), '</div>';
			echo $this->Form->TextBox('Description2', array('multiline' => TRUE));
		?>
	</li>
</ul>
<?php
echo $this->Form->Close('Save');