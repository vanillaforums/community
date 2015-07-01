<?php if (!defined('APPLICATION')) exit();
include($this->FetchViewLocation('helper_functions'));
if ($this->DeliveryType() == DELIVERY_TYPE_ALL)
    echo $this->FetchView('head');

if ($this->ApprovedData->NumRows() > 0) {
?>
<h2>Vanilla Approved</h2>
<ul class="DataList Addons">
    <?php
    $Alt = '';
    foreach ($this->ApprovedData->Result() as $Addon) {
        $Alt = $Alt == ' Alt' ? '' : ' Alt';
        WriteAddon($Addon, $Alt);
    }
    ?>
</ul>
<?php
}

if ($this->NewData->NumRows() > 0) {
?>
<h2>Recently Uploaded &amp; Updated</h2>
<ul class="DataList Addons">
    <?php
    $Alt = '';
    foreach ($this->NewData->Result() as $Addon) {
        $Alt = $Alt == ' Alt' ? '' : ' Alt';
        WriteAddon($Addon, $Alt);
    }
    ?>
</ul>
<?php
echo Anchor('More', '/addon/browse', array('class' => 'More'));
}

