<?php if (!defined('APPLICATION')) exit();
$this->HideSearch = TRUE;
if ($this->DeliveryType() == DELIVERY_TYPE_ALL)
    echo $this->FetchView('head');

?>
<h2><?php echo T('Upload a New Version'); ?></h2>
<div class="Info"><?php
    echo T('Addons allowable license info', "All addons must declare an appropriate GPL2-compatible license.
    These include: GNU GPL2, MIT, BSD, GNU LGPL, or Mozilla Public License (MPL) v2.
    Addons uploaded without explicit license information will be declared as GNU GPL2.
    By uploading your new or revised addon, you agree to these terms.");
?></div>
<?php
    echo $this->Form->Open(array('enctype' => 'multipart/form-data'));
    echo $this->Form->Errors();
?>
<ul>
    <li>
        <h3><?php echo $this->Form->Label('File to Upload (2mb max)', 'File'); ?></h3>
        <?php echo $this->Form->Input('File', 'file', array('class' => 'File'));
        echo '<div class="Info">', T('By uploading a file you certify that you have the right to distribute this addon and that it does not violate the Terms of Service.'), '</div>';
        ?>
    </li>
    <!--<li>
        <?php
//            echo $this->Form->Label('New Version Number', 'Version');
//            echo $this->Form->TextBox('Version');
        ?>
    </li>-->
    <!--
    <li>
        <div class="Info"><?php echo T('Specify which versions you have tested the new version of your addon with: PHP, MySQL, jQuery, etc'); ?></div>
        <?php
//            echo $this->Form->Label('Testing Information', 'TestedWith');
//            echo $this->Form->TextBox('TestedWith', array('multiline' => TRUE));
        ?>
    </li>
    -->
</ul>
<?php echo $this->Form->Close('Upload');