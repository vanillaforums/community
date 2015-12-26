<?php if (!defined('APPLICATION')) exit();

$this->HideSearch = TRUE;
if ($this->deliveryType() == DELIVERY_TYPE_ALL)
    echo $this->fetchView('head');
?>

<h1><?php echo t('Upload Icon'); ?></h1>

<div class="Info"><?php echo t('By uploading a file you certify that you have the right to distribute this picture and that it does not violate the Terms of Service.'); ?></div>
<?php
echo $this->Form->open(array('enctype' => 'multipart/form-data'));
echo $this->Form->errors();
?>
<ul>
    <li>
        <h3><?php echo $this->Form->label('Choose Icon (2mb max)', 'Icon'); ?></h3>
        <?php echo $this->Form->input('Icon', 'file', array('class' => 'File')); ?>
    </li>
</ul>
<?php echo $this->Form->close('Upload');