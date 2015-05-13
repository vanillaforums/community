<?php if (!defined('APPLICATION')) exit();
echo Wrap(T('Edit Attached Addon'), 'h2');

echo $this->Form->Open();
echo $this->Form->Errors();
?>
<ul>
    <li>
        <?php
        echo $this->Form->Label('Addon Name', 'Name');
        echo $this->Form->TextBox('Name');
        ?>
    </li>
    <li>
        <?php
        echo $this->Form->Label('Addon ID', 'AddonID');
        echo $this->Form->TextBox('AddonID');
        ?>
    </li>
    <li>
        <?php
        echo $this->Form->CheckBox('RemoveAttachment', 'Remove the addon attachment?');
        ?>
    </li>
</ul>
<?php
echo $this->Form->Close('Save');
