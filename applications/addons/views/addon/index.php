<?php if (!defined('APPLICATION')) exit();

include($this->fetchViewLocation('helper_functions'));

if ($this->deliveryType() == DELIVERY_TYPE_ALL)
    echo $this->fetchView('head');

if ($this->ApprovedData->numRows() > 0) {
?>
<h2>Vanilla Approved</h2>
<ul class="DataList Addons">
    <?php
    $Alt = '';
    foreach ($this->ApprovedData->result() as $Addon) {
        $Alt = $Alt == ' Alt' ? '' : ' Alt';
        writeAddon($Addon, $Alt);
    }
    ?>
</ul>
<?php
}

if ($this->NewData->numRows() > 0) {
?>
<h2>Recently Uploaded &amp; Updated</h2>
<ul class="DataList Addons">
    <?php
    $Alt = '';
    foreach ($this->NewData->result() as $Addon) {
        $Alt = $Alt == ' Alt' ? '' : ' Alt';
        writeAddon($Addon, $Alt);
    }
    ?>
</ul>
<?php
echo anchor('More', '/addon/browse', array('class' => 'More'));
}

