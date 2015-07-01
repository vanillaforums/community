<?php if (!defined('APPLICATION')) exit();
// Create some variables so that they aren't defined in every loop.

include($this->FetchViewLocation('helper_functions', 'addon', 'addons'));
?>
<ul class="DataList Addons">
<?php
if ($this->Data('Addons')->NumRows() == 0) {
    echo '<li class="Empty">This user has not contributed any addons.</li>';
}

$Alt = '';
foreach ($this->Data('Addons')->Result() as $Addon) {
    $Alt = $Alt == ' Alt' ? '' : ' Alt';
    WriteAddon($Addon, $Alt);
}
?></ul>
