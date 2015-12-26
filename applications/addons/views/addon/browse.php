<?php if (!defined('APPLICATION')) exit();

include($this->fetchViewLocation('helper_functions'));

if ($this->deliveryType() == DELIVERY_TYPE_ALL) {
    echo $this->fetchView('head');
?>
    <h1><?php echo $this->data('Title'); ?></h1>
    <ul class="DataList Addons">
        <?php
        if ($this->data('Addons')->numRows() == 0) {
            echo '<li class="Empty">There were no addons matching your search criteria.</li>';
            if ($this->Filter != 'all') {
                echo '<li class="Empty">Try choosing to show <code>Everything</code> above instead.</li>';
            }
        }
}
$Alt = '';
foreach ($this->data('Addons')->result() as $Addon) {
    $Alt = $Alt == ' Alt' ? '' : ' Alt';
    writeAddon($Addon, $Alt);
}
if ($this->deliveryType() == DELIVERY_TYPE_ALL && $this->data('_Pager')) {
?>
    </ul>
    <?php
    echo $this->data('_Pager')->toString('more');
} else {
?></ul><?php
}