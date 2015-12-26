<?php if (!defined('APPLICATION')) exit();

$this->HideSearch = TRUE;
if ($this->deliveryType() == DELIVERY_TYPE_ALL)
    echo $this->fetchView('head');
?>

<h1><?php echo t('Create a new Addon'); ?></h1>

<div class="Info"><?php
    echo t('Addons allowable license info', "All addons must declare an appropriate GPL2-compatible license.
    These include: GNU GPL2, MIT, BSD, GNU LGPL, or Mozilla Public License (MPL) v2.
    Addons uploaded without explicit license information will be declared as GNU GPL2.
    By uploading your new or revised addon, you agree to these terms.");
    //echo sprintf(t('This page is only for Vanilla 2 addons.', 'This page is only for adding Vanilla 2 addons. If you want to add a Vanilla 1 addon click <a href="%s">here</a>.'), Url('/addon/addv1'));
?></div>
<?php
    echo $this->Form->open(array('enctype' => 'multipart/form-data'));
    echo $this->Form->errors();
?>
<ul>
    <li>
        <?php
            echo '<h3>', $this->Form->label('Addon Archive (2mb max, must be a zip archive)', 'File'), '</h3>';
            echo $this->Form->input('File', 'file', array('class' => 'File'));
            echo '<div class="Info">', t('By uploading a file you certify that you have the right to distribute the file and that it does not violate the Terms of Service.'), '</div>';
        ?>
    </li>
    <li>
        <?php
            echo '<h3>', $this->Form->label('Additional Description', 'Description2'), '</h3>';
            echo $this->Form->textBox('Description2', array('multiline' => true));
            echo '<div class="Info">', t('Additional Description', 'Your addon file should contain a basic description in it\'s info array. Add an additional, more detailed description here. Html allowed.'), '</div>';
        ?>
    </li>
</ul>
<?php echo $this->Form->close('Save');
