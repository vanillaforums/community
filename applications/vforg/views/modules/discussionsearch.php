<?php if (!defined('APPLICATION')) exit(); ?>
<div class="SearchForm">
    <?php
    $Form = Gdn::Factory('Form');
    $Form->InputPrefix = '';
    echo
        $Form->Open(array('action' => Url('/search'), 'method' => 'get')),
        $Form->TextBox('Search'),
        $Form->Button('Search', array('Name' => '')),
        $Form->Close();
    ?>
</div>