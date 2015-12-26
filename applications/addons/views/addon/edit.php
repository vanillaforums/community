<?php if (!defined('APPLICATION')) exit();

$this->HideSearch = TRUE;
if ($this->deliveryType() == DELIVERY_TYPE_ALL)
    echo $this->fetchView('head');

?>
<h1><?php echo t('Edit Addon'); ?></h1>
<?php
echo $this->Form->open();
echo $this->Form->errors();
?>
<ul>
    <li>
        <?php
            echo '<h3>', $this->Form->label('Additional Description', 'Description2'), '</h3>';
            echo '<div class="Info">', t('Additional Description', 'Your addon file should contain a basic description in it\'s info array. Add an additional, more detailed description here. Html allowed.'), '</div>';
            echo $this->Form->textBox('Description2', array('multiline' => TRUE));
        ?>
    </li>
</ul>
<?php
echo $this->Form->close('Save');