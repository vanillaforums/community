<?php if (!defined('APPLICATION')) exit();

$this->HideSearch = TRUE;
if ($this->deliveryType() == DELIVERY_TYPE_ALL)
    echo $this->fetchView('head');

?>
<h2><?php echo t('Upload a New Version'); ?></h2>
<div class="Info"><?php
    echo t('Addons allowable license info', "All addons must declare an appropriate GPL2-compatible license.
    These include: GNU GPL2, MIT, BSD, GNU LGPL, or Mozilla Public License (MPL) v2.
    Addons uploaded without explicit license information will be declared as GNU GPL2.
    By uploading your new or revised addon, you agree to these terms.");
?></div>
<?php
    echo $this->Form->open(array('enctype' => 'multipart/form-data'));
    echo $this->Form->errors();
?>
<ul>
    <li>
        <h3><?php echo $this->Form->label('File to Upload (2mb max)', 'File'); ?></h3>
        <?php echo $this->Form->input('File', 'file', array('class' => 'File'));
        echo '<div class="Info">', t('By uploading a file you certify that you have the right to distribute this addon and that it does not violate the Terms of Service.'), '</div>';
        ?>
    </li>
</ul>
<?php echo $this->Form->close('Upload');