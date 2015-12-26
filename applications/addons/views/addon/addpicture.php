<?php if (!defined('APPLICATION')) exit();
$this->HideSearch = TRUE;
if ($this->deliveryType() == DELIVERY_TYPE_ALL)
    echo $this->fetchView('head');

?>
<h1><?php echo t('Add a Picture'); ?></h1>
<div class="Info"><?php echo t('By uploading a picture you certify that you have the right to distribute this picture and that it does not violate the Terms of Service.'); ?></div>
<?php
echo $this->Form->open(array('enctype' => 'multipart/form-data'));
echo $this->Form->errors();
?>
<ul>
    <li>
        <h3><?php echo $this->Form->label('Picture to Upload (2mb max)', 'Picture'); ?></h3>
        <?php echo $this->Form->input('Picture', 'file', array('class' => 'File')); ?>
    </li>
</ul>
<?php echo $this->Form->close('Upload');