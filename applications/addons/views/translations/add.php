<?php if (!defined('APPLICATION')) exit(); ?>
<div class="Form">
    <h2><?php echo T('Create a New Translation'); ?></h2>
    <?php
    echo $this->Form->Open();
    echo $this->Form->Errors();
    ?>
    <ul>
        <li>
            <?php
                echo $this->Form->Label('Language', 'LanguageID');
                echo $this->Form->DropDown(
                    'LanguageID',
                    $this->LanguageData,
                    array(
                        'ValueField' => 'LanguageID',
                        'TextField' => 'Label',
                        'IncludeNull' => FALSE
                    ));
            ?>
        </li>
    </ul>
    <?php echo $this->Form->Close('Get Started'); ?>
</div>