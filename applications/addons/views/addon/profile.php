<?php if (!defined('APPLICATION')) exit();
// Create some variables so that they aren't defined in every loop.

include($this->fetchViewLocation('helper_functions', 'addon', 'addons'));
?>
<ul class="DataList Addons">
<?php
if ($this->data('Addons')->numRows() == 0) {
    echo '<li class="Empty">This user has not contributed any addons.</li>';
}

$Alt = '';
foreach ($this->data('Addons')->result() as $Addon) {
    $Alt = $Alt == ' Alt' ? '' : ' Alt';
    writeAddon($Addon, $Alt);
}
?></ul>
