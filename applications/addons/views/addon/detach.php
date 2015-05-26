<?php if (!defined('APPLICATION')) exit();
echo Wrap(T('Detach Addon from Discussion'), 'h2');

echo $this->Form->Open();
echo $this->Form->Errors();
?>
<ul>
    <li>
        <?php
        echo $this->Form->CheckBox('DetachConfirm', 'Are you sure you want to remove the addon attachment?');
        ?>
    </li>
</ul>
<?php
echo $this->Form->Close('Detach');
